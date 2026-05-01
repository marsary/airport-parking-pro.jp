<?php
namespace App\Services\Soc;

use App\CmsModels\CarColorTbl;
use App\CmsModels\CarTbl;
use App\CmsModels\GoodsTbl;
use App\Enums\CarSize;
use App\Enums\DealStatus;
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
        // car_id	member_cars.car_id		エラー発生、全体処理をスキップ、エラーログ
        $this->checkModelIdExists($syncDealRecord, CarTbl::class, 'car_id', true, $deal->memberCar->car_id);
        // car_col_id	member_cars.car_color_id		スキップ
        $this->checkModelIdExists($syncDealRecord, CarColorTbl::class, 'car_col_id', false, $deal->memberCar->car_color_id);
        foreach ($deal->dealGoods as $dealGood) {
            // goods.g_id	deal_goods.good_id		エラー発生、全体処理をスキップ、エラーログ
            $this->checkModelIdExists($syncDealRecord, GoodsTbl::class, 'g_id', true, $dealGood->good->id);
        }

        // 代理店コードの変換
        $this->convAgentIds($syncDealRecord);
    }

    private function convAgentIds(SyncDealRecord $syncDealRecord)
    {
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
}

class SyncDealRecord
{
    public $deal;
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
            $goods[] = [
                'g_id' => $dealGood->good_id,
                'price' => $dealGood->good->price, // 1個あたり税抜単価
                'num' => $dealGood->num,
            ];
        }

        return [
            'ambiguous_flg' => null, // あいまいフラグ（1で強制確認）
            'rsv_id' => $this->deal->id, // SOC予約ID（rsv_id1と同じ）
            'rsv_id1' => $this->deal->id, //
            'group_type' => 0, // 0:非グループ固定 (0:非グループ, 1:代表, 2:メンバー)
            'rsv_id_group' => null , // グループID NULL固定
            'o_id' => $this->deal->office_id, // 事業所ID（1:成田, 2:レッド等）
            'member_flg' => $this->deal->member->soc_member_flg, // 会員フラグ
            'u_id' => $this->deal->member->soc_member_id, // 顧客ID（member_id）
            'rsv_date' => $this->deal->reserve_date, // 予約受付日時
            'name' => $this->deal->name, // 漢字氏名（全角スペース変換済）
            'name_k' => $this->deal->kana, // カナ氏名（全角スペース変換済）
            'zip' => $this->deal->zip, //
            'addr1' => $this->deal->member->address1, //
            'addr2' => $this->deal->member->address2, //
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
            'flt_corp_id' => $this->deal->arrivalFlight?->airline->code, // 航空会社コード
            'flt_id' => $this->deal->arrivalFlight?->flight_no, // 便名
            'flt_dpt_id' => $this->deal->arrivalFlight?->depAirport->code, // 出発地ID
            'car_id' => $this->car_id, // 車種ID
            'car_number' => $this->deal->memberCar->number, // 車番（下4桁）
            'car_col_id' => $this->car_col_id, // 色ID
            'user_num' => $this->deal->num_members, // 利用人数
            'lsize_flg' => $this->deal->memberCar->car->size_type == CarSize::LARGE->value, // Lサイズフラグ
            'dsc_rate' => $this->deal->dsc_rate, // 割引率
            'days' => $this->deal->num_days, // 日数。保留分は含まない。 ParkingProでは、保留分を都度更新するが、新規登録のみ対象のため問題ない
            'price' => $this->deal->price, // 利用料金（率割引後，税抜き）
            'price_tax' => $this->deal->tax, // 利用料金消費税
            'cancel_flg' => $this->deal->status == DealStatus::CANCEL->value, // キャンセルフラグ

            'goods' => $goods, // オプション商品
        ];
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
}
