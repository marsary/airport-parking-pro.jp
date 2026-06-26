<?php
namespace App\Services\Soc;

use App\CmsModels\CarColorTbl;
use App\CmsModels\CarTbl;
use App\CmsModels\DscTicketTbl;
use App\CmsModels\GoodsTbl;
use App\Enums\CarSize;
use App\Enums\Cms\SocOfficeId;
use App\Enums\Cms\SocSalesType;
use App\Enums\Cms\SocTaxType;
use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Models\Airline;
use App\Models\Deal;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDealService
{
    protected array $syncDealRecords = [];
    protected const AG_ID1 = 6033;
    protected const AG_ID2 = 71;

    public function setDeals(Collection $deals): self
    {
        $this->syncDealRecords = []; // 複数回呼ばれる可能性を考慮してリセット
        foreach ($deals as $deal) {
            $this->syncDealRecords[] = new SyncDealRecord($deal);
        }
        return $this;
    }

    public function getSortedDataForSync()
    {
        $syncData = [];
        foreach ($this->syncDealRecords as $syncDealRecord) {
            try {
                DB::transaction(function () use($syncDealRecord, &$syncData) {
                    /** @var SyncDealRecord $syncDealRecord */
                    $this->convertToCmsData($syncDealRecord);

                    $syncData[$syncDealRecord->deal->id] = $syncDealRecord->format();
                });

            } catch (\Throwable $th) {
                Log::error('エラー内容：' . $th->getMessage());
            }
        }

        return $syncData;
    }

    private function convertToCmsData(SyncDealRecord $syncDealRecord)
    {
        // 取得データを必要に応じてCMS のマスタデータに変換する
        $deal = $syncDealRecord->deal;

        // 変換処理時にID存在確認を実施する。
        // 送信フィールド	DBカラム		不存在時
        // office_id エラー発生、全体処理をスキップ、エラーログ
        $this->checkOfficeIdExists($deal->office_id);
        // car_id	member_cars.car_id		エラー発生、全体処理をスキップ、エラーログ
        $this->checkModelIdExists($syncDealRecord, CarTbl::class, 'car_id', true, $deal->memberCar->car_id);
        // car_col_id	member_cars.car_color_id		スキップ
        $this->checkModelIdExists($syncDealRecord, CarColorTbl::class, 'car_col_id', false, $deal->memberCar->car_color_id);
        // dt_id	deals.payments.payment_details.coupon_id		スキップ
        $this->checkModelIdExists($syncDealRecord, DscTicketTbl::class, 'dt_id', false, $syncDealRecord->getAppliedCoupon()?->coupon_id);
        foreach ($deal->dealGoods as $dealGood) {
            // goods.g_id	deal_goods.good_id		エラー発生、全体処理をスキップ、エラーログ
            $this->checkModelIdExists($syncDealRecord, GoodsTbl::class, 'g_id', true, $dealGood->good->id);
            // goods.tax_type	全て内税なので、確認不要
            // goods.sales_type	deal_goods.goods.good_categories		スキップ
            $this->checkSalesTypeExists($syncDealRecord, $dealGood->id, $dealGood->good->good_category_id);
        }

        // 代理店コードの変換
        $this->convAgentIds($syncDealRecord);
    }

    private function convAgentIds(SyncDealRecord $syncDealRecord)
    {
        // SOCでは、ag_id1, ag_id2 の組み合わせで構成。新システムでは agents.id のみ
        // 代理店コードは固定になる
        // 以下を固定で設定
        // ag_id1：6033
        // ag_id2：71  もしかしたら、ag_id2は72になるかも
        // この代理店は、「サンホームページ(公式HPWEB割)」
        $syncDealRecord->agId1 = self::AG_ID1;
        $syncDealRecord->agId2 = self::AG_ID2;
    }

    private function checkModelIdExists(SyncDealRecord $syncDealRecord, $modelClass, $columnName, $throw = false, ?int $columnId)
    {
        if(!$columnId) {
            return;
        }

        $record = $modelClass::where($columnName, $columnId)->first();
        if(!$record) {
            $errorMessage = (new $modelClass)->getTable() . '.' . $columnName . 'に、ID' . $columnId . ' が存在しません。';

            if($throw) {
                throw new ErrorException($errorMessage. 'この取引の同期処理をスキップします。');
            }
            Log::info($errorMessage . '値がないものとして処理を続行します。');

            return false;
        }
        $syncDealRecord->{$columnName} = $columnId;

        return true;
    }

    private function checkSalesTypeExists(SyncDealRecord $syncDealRecord, int $deailGoodId, ?int $salesTypeId)
    {
        if(!$salesTypeId) {
            return;
        }
        $salesType = SocSalesType::tryFrom($salesTypeId);
        if(!$salesType) {
            $errorMessage = '売上区分に、ID' . $salesTypeId . ' が存在しません。';
            Log::info($errorMessage . '値がないものとして処理を続行します。');
            return false;
        }
        $syncDealRecord->salesTypes[$deailGoodId] = $salesTypeId;
        return true;
    }

    private function checkOfficeIdExists(int $officeId)
    {
        $office = SocOfficeId::tryFrom($officeId);
        if(!$office) {
            $errorMessage = '事業所IDに、ID' . $officeId . ' が存在しません。';
            throw new ErrorException($errorMessage . 'この取引の同期処理をスキップします。');
        }
        return true;
    }

}

class SyncDealRecord
{
    public $deal;
    public $appliedCoupon;
    public $agId1;
    public $agId2;
    public $salesTypes = [];
    public $car_id;
    public $car_col_id;
    public $dt_id;

    function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }

    public function format()
    {
        $goods = [];
        foreach ($this->deal->dealGoods as $dealGood) {
            $salesTypeId = $this->salesTypes[$dealGood->id] ?? null;

            $goods[] = [
                'g_id' => $dealGood->good_id,
                'price' => $dealGood->good->price, // 1個あたり税抜単価
                'price_tax' => $dealGood->good->tax, // 1個あたり消費税
                'total_price' => $dealGood->total_price + $dealGood->total_tax, // 単価（税込）× 数量
                'tax_type' => SocTaxType::MY_TAX_TYPE_IN->value,  // 全て内税
                'sales_type' => $salesTypeId, // 売上区分
                'num' => $dealGood->num,
            ];
        }

        $couponDetail = $this->getAppliedCoupon();

        $soc_member_flg = 0;
        if(isset($this->deal->member?->soc_member_flg)) {
            $soc_member_flg = $this->deal->member->soc_member_flg;
        }
        $soc_member_id = 0;
        if(isset($this->deal->member?->soc_member_id)) {
            $soc_member_id = $this->deal->member->soc_member_id;
        }
        $address1 = '';
        if(isset($this->deal->member?->address1)) {
            $address1 = $this->deal->member->address1;
        }
        $address2 = '';
        if(isset($this->deal->member?->address2)) {
            $address2 = $this->deal->member->address2;
        }

        return [
            'ambiguous_flg' => null, // あいまいフラグ（1で強制確認）
            'rsv_id' => $this->deal->id, // SOC予約ID（rsv_id1と同じ）
            'rsv_id1' => $this->deal->id, //
            'group_type' => 0, // 0:非グループ固定 (0:非グループ, 1:代表, 2:メンバー)
            'rsv_id_group' => null , // グループID NULL固定
            'o_id' => $this->deal->office_id, // 事業所ID（1:成田, 2:レッド等）
            'member_flg' => $soc_member_flg, // 会員フラグ
            'u_id' => $soc_member_id, // 顧客ID（member_id）
            'rsv_date' => $this->deal->reserve_date, // 予約受付日時
            'name' => $this->deal->name, // 漢字氏名（全角スペース変換済）
            'name_k' => $this->deal->kana, // カナ氏名（全角スペース変換済）
            'zip' => $this->deal->zip, //
            'addr1' => $address1, //
            'addr2' => $address2, //
            'tel' => $this->deal->tel, //
            'tel_mb' => null, //
            'email' => $this->deal->email, //
            'email_mb' => null, //
            'mailmag_flg' => null, // メルマガ希望
            'ag_id1' => $this->agId1, // SOC用代理店ID（メイン）
            'ag_id2' => $this->agId2, // SOC用代理店ID（枝番）
            'visit_date_plan' => $this->formatDate($this->deal->visit_date_plan), // 来店予定日
            'visit_time_plan' => $this->formatTime($this->deal->visit_time_plan), // 来店予定時間
            'load_date' => $this->formatDate($this->deal->load_date), // 入庫日
            'load_time' => $this->formatTime($this->deal->load_time), // 入庫時間
            'unload_date_plan' => $this->formatDate($this->deal->unload_date_plan), // 出庫予定日
            'unload_time_plan' => $this->formatTime($this->deal->unload_time_plan), // 出庫予定時間
            'flt_corp_id' => $this->getFlightCorpId($this->deal), // 航空会社コード
            'flt_id' => $this->deal->arrivalFlight?->flight_no ?? $this->deal->flight_no, // 便名
            'flt_dpt_id' => $this->deal->arrivalFlight?->depAirport->code, // 出発地ID
            'car_id' => $this->car_id, // 車種ID
            'car_number' => $this->deal->memberCar?->number, // 車番（下4桁）
            'car_col_id' => $this->car_col_id, // 色ID
            'user_num' => $this->deal->num_members, // 利用人数
            'lsize_flg' => $this->deal->memberCar?->car?->size_type == CarSize::LARGE->value, // Lサイズフラグ
            'dsc_rate' => $this->deal->dsc_rate, // 割引率
            'dt_id' => $this->dt_id, // 割引券ID
            'dt_price' => $couponDetail? $this->calcCouponPriceWithTax($couponDetail->total_price) : null, // 割引券による割引額（税込み）
            'days' => $this->deal->num_days, // 日数。保留分は含まない。 ParkingProでは、保留分を都度更新するが、新規登録のみ対象のため問題ない
            'price' => $this->deal->price, // 利用料金（率割引後，税抜き）
            'price_tax' => $this->deal->tax, // 利用料金消費税
            'total_pay' => $this->deal->payment?->total_pay, // 支払合計金額（税込み）
            'cancel_flg' => $this->deal->status == DealStatus::CANCEL->value, // キャンセルフラグ

            'goods' => $goods, // オプション商品

            // 未実装
            'prepaid_state' => null, // 事前決済ステータス（2:与信完了等）
            'prepaid2_pay' => null, // 事前決済金額
            'prepaid2_jcb' => null, // 事前決済JCBフラグ
            'trans_note' => null, // 備考（鍵・洗車・マイル等を結合）
            //
        ];
    }

    public function getAppliedCoupon()
    {
        if($this->appliedCoupon) {
            return  $this->appliedCoupon;
        }

        $appliedCoupons = collect([]);
        if($this->deal->payment?->paymentDetails) {
            foreach ($this->deal->payment->paymentDetails as $paymentDetail) {
                if($paymentDetail->coupon()->exists()) {
                    $appliedCoupons->push($paymentDetail);
                }
            }
        }
        // 要素が２以上ならばエラーにする
        if($appliedCoupons->count() > 1) {
            throw new ErrorException('本APIでは、複数クーポン使用には対応していません。Deal ID:' . $this->deal->id);
        }


        return $this->appliedCoupon = $appliedCoupons->first();
    }

    private function formatTime($time)
    {
        if(! $time instanceof Carbon) {
            $time = Carbon::parse($time);
        }

        return $time->format("H:i");
    }

    private function formatDate($date)
    {
        if(! $date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->format("Y-m-d");
    }

    private function calcCouponPriceWithTax(int $price)
    {
        // クーポンは常に10％消費税
        return roundTax(TaxType::TEN_PERCENT->rate() * $price) + $price;
    }


    private function getFlightCorpId(Deal $deal)
    {
        if(isset($deal->arrivalFlight?->airline)) {
            return $deal->arrivalFlight->airline->code;
        }
        if(isset($deal->airline_id)) {
            $airline = Airline::find($deal->airline_id);
            return $airline?->code;
        }
        return null;
    }
}
