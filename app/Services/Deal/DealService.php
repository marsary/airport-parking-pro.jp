<?php
namespace App\Services\Deal;

use App\Enums\DealStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Manage\Forms\DealEditForm;
use App\Models\Deal;
use App\Models\Member;
use App\Services\Member\ReserveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DealService extends ReserveService
{
    /** @var DealEditForm */
    public $reserve;


    function __construct(DealEditForm $reserve)
    {
        $this->reserve = $reserve;
        $this->deal = $this->reserve->deal;
    }

    public function update()
    {
        $this->updateMember();
        $this->saveMemberCar();
        $this->saveCarCautions();
        $this->updateDeal();
    }


    protected function updateMember()
    {
        if(!$this->reserve->member) {
            return;
        }
        $member = Member::findOrFail($this->reserve->member->id);

        return $member->fill([
            'name' => $this->reserve->name,
            'kana' => $this->reserve->kana,
            'zip' => $this->reserve->zip,
            'tel' => $this->reserve->tel,
            'email' => $this->reserve->email,
            'memo' => $this->reserve->member_memo,
        ])->save();
    }


    private function updateDeal()
    {
        $this->deal->fill([
            'member_id' => $this->reserve->member_id,
            'office_id' => $this->reserve->office_id,
            'agency_id' => $this->reserve->agency_id,
            'status' => $this->reserve->status,
            'reserve_code' => $this->reserve->reserve_code,
            // 'receipt_code' => $this->reserve->receipt_code, この時点では受付コードはない
            'reserve_date' => $this->reserve->reserve_date,
            'load_date' => $this->reserve->load_date,
            'load_time' => $this->reserve->load_time,
            'unload_date_plan' => $this->reserve->unload_date_plan,
            'unload_time_plan' => $this->reserve->unload_time_plan,
            'arrival_flg' => $this->reserve->arrival_flg,
            'visit_date_plan' => $this->reserve->visit_date_plan,
            'num_days' => $this->reserve->num_days,
            'num_members' => $this->reserve->num_members ?? 1,
            'name' => $this->reserve->name,
            'kana' => $this->reserve->kana,
            'zip' => $this->reserve->zip,
            'tel' => $this->reserve->tel,
            'email' => $this->reserve->email,
            'dsc_rate' => $this->reserve->dsc_rate,
            'price' => $this->reserve->price,
            'tax' => $this->reserve->tax,
            'total_price' => $this->reserve->total_price,
            'total_tax' => $this->reserve->total_tax,
            'arr_flight_id' => $this->reserve->arr_flight_id,
            'member_car_id' => $this->reserve->member_car_id,
            'receipt_address' => $this->reserve->receipt_address,
            'reserve_memo' => $this->reserve->reserve_memo,
            'reception_memo' => $this->reserve->reception_memo,
            'remarks' => $this->reserve->remarks,
        ])->save();

        $this->updateDealGoods();
    }


    private function updateDealGoods()
    {
        $this->deal->dealGoods()->delete();
        $this->createDealGoods();
    }


    public static function makePurchaseOnly()
    {
        return Deal::make([
            'member_id' => null,
            'office_id' => config('const.commons.office_id'),
            'agency_id' => null,  // 代理店設定なし
            'transaction_type' => TransactionType::PURCHASE_ONLY->value,
            'status' => DealStatus::UNLOADED->value,
            'reserve_code' => Str::ulid(),
            'num_members' => 0,
            'name' => null,
            'kana' => null,
            'price' => 0,
            'tax' => 0,
            'total_price' => 0,
            'total_tax' => 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public static function createPurchaseOnly()
    {
        $purchaseOnlyData = self::makePurchaseOnly();

        Deal::doesntHave('payment')->each(function (Deal $deal) {
            $deal->dealGoods()->delete(); // Deal に紐づく DealGood を削除
            $deal->delete(); // Deal を削除
        });

        $purchaseOnlyData->save();
        return $purchaseOnlyData;
    }
}
