<?php
namespace App\Services\Member;

use App\Http\Controllers\Member\Forms\ReserveForm;
use App\Models\Deal;
use App\Models\DealGood;
use App\Models\Member;
use App\Models\MemberCar;
use Illuminate\Support\Facades\DB;

class ReserveService
{
    /** @var ReserveForm */
    private $reserve;

    /** @var Deal */
    public $deal;


    function __construct(ReserveForm $reserve)
    {
        $this->reserve = $reserve;
    }

    public function store()
    {
        $this->updateMember();
        $this->saveMemberCar();
        $this->createDeal();
    }


    private function updateMember()
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
        ])->save();
    }

    private function saveMemberCar()
    {
        $memberCar = null;
        if($this->reserve->member_car_id) {
            $memberCar = MemberCar::find($this->reserve->member_car_id);
        }

        if($memberCar) {
            $memberCar = $memberCar->fill([
                'office_id' => config('const.commons.office_id'),
                'member_id' => $this->reserve->member?->id,
                'car_id' => $this->reserve->car_id,
                'car_color_id' => $this->reserve->car_color_id,
                'number' => $this->reserve->car_number,
                'default_flg' => 1, // 予約で使用したものをデフォルトに設定
            ])->save();

            // 予約で使用したもの以外をデフォルトから外す
            if($this->reserve->member) {
                DB::table('member_cars')->where('member_id', $this->reserve->member->id)
                    ->whereNot('id', $memberCar->id)
                    ->where('office_id', config('const.commons.office_id'))
                    ->update(['default_flg', false]);
            }
        } else {
            $memberCar = MemberCar::create([
                'office_id' => config('const.commons.office_id'),
                'member_id' => $this->reserve->member?->id,
                'car_id' => $this->reserve->car_id,
                'car_color_id' => $this->reserve->car_color_id,
                'number' => $this->reserve->car_number,
                'default_flg' => 1, // 予約で使用したものをデフォルトに設定
            ]);

            $this->reserve->member_car_id = $memberCar->id;
        }


    }

    private function createDeal()
    {
        $this->deal = Deal::create([
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
            'num_days' => $this->reserve->num_days,
            'num_members' => $this->reserve->num_members,
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
        ]);

        $this->createDealGoods();
    }


    private function createDealGoods()
    {
        foreach ($this->reserve->dealGoodData as $newDealGood) {
            $newDealGood['deal_id'] = $this->deal->id;
            $this->deal->dealGoods[] = DealGood::create($newDealGood);
        }
    }

}
