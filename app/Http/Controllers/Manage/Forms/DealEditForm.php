<?php
namespace App\Http\Controllers\Manage\Forms;

use App\Models\Agency;
use App\Models\Deal;
use App\Models\MemberCar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DealEditForm extends ManageReserveForm
{
    public $deal;

    public $member_memo;

    function __construct(Deal $deal)
    {
        $this->deal = $deal;
        if($deal->member) {
            $this->member_id = $deal->member->id;
            $this->member = $deal->member;
        }
        $this->setMemberAndCarFromDeal($deal);

        $this->office_id = $deal->office_id;
        $this->agency_id = $deal->agency_id;
        $this->agency_code = Agency::find($deal->agency_id)?->code;
        $this->status = $deal->status;
        $this->reserve_code = $deal->reserve_code;
        $this->receipt_code = $deal->receipt_code;
        $this->reserve_date = Carbon::now();
        $this->load_date = $deal->load_date;
        $this->load_time = $deal->load_time;
        $this->unload_date_plan = $deal->unload_date_plan;
        $this->unload_time_plan = $deal->unload_time_plan;
        $this->arrival_flg = $deal->arrival_flg;
        $this->visit_date_plan = $deal->visit_date_plan;
        $this->num_days = $deal->num_days;
        $this->num_members = $deal->num_members;

        $this->dsc_rate = $deal->dsc_rate;
        $this->price = $deal->price;
        $this->tax = $deal->tax;
        $this->total_price = $deal->total_price;
        $this->total_tax = $deal->total_tax;
        $this->arr_flight_id = $deal->arr_flight_id;
        $this->size_type = $deal->memberCar?->car->size_type;
        $this->receipt_address = $deal->receipt_address;
        $this->member_memo = $deal->member?->memo;
        $this->reserve_memo = $deal->reserve_memo;
        $this->reception_memo = $deal->reception_memo;
        $this->remarks = $deal->remarks;

        $this->good_ids = $deal->dealGoods()->pluck('good_id')->toArray();
        $this->good_nums = $deal->dealGoods()->pluck('num', 'good_id')->toArray();
        $this->airline_id = $deal->arrivalFlight?->airline_id;
        $this->flight_no = $deal->arrivalFlight?->flight_no;
        $this->arrive_date = $deal->arrivalFlight?->arrive_date;
        $this->arrive_time = $deal->arrivalFlight?->arrive_time;

    }

    private function setMemberAndCarFromDeal(Deal $deal)
    {
        $this->name = $deal->name;
        $this->kana = $deal->kana;
        $this->zip = $deal->zip;
        $this->tel = $deal->tel;
        $this->email = $deal->email;
        $this->member_car_id = $deal->member_car_id;

        $memberCar = MemberCar::with('car.carMaker')->where('id', $deal->member_car_id)
            ->first();

        if($memberCar) {
            $this->car_maker_id = $memberCar->car->carMaker->id;
            $this->car_id = $memberCar->car_id;
            $this->car_color_id = $memberCar->car_color_id;
            $this->car_number = $memberCar->number;
            $this->member_car_id = $memberCar->id;
        }
        $this->car_caution_ids = DB::table('car_cautions')
            ->leftJoin('car_caution_member_cars', 'car_cautions.id', '=', 'car_caution_member_cars.car_caution_id')
            ->where('car_caution_member_cars.member_car_id', $this->member_car_id)
            ->whereNull('car_caution_member_cars.deleted_at')
            ->pluck('car_cautions.id')->toArray();
    }
}
