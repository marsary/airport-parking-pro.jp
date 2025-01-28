<?php
namespace App\Http\Controllers\Manage\Forms;

use App\Http\Controllers\Forms\ReserveFormBase;
use App\Models\CarCaution;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class ManageReserveForm extends ReserveFormBase
{
    public $car_caution_ids = [];

    public $arrive_time;


    function __construct()
    {
        parent::__construct();
    }

    public function setMember(Member $member = null)
    {
        $this->member = $member;
        parent::setMember($member);

        if($this->member_car_id) {
            $this->car_caution_ids = DB::table('car_cautions')
                ->leftJoin('car_caution_member_cars', 'car_cautions.id', '=', 'car_caution_member_cars.car_caution_id')
                ->where('car_caution_member_cars.member_car_id', $this->member_car_id)
                ->whereNull('car_caution_member_cars.deleted_at')
                ->pluck('car_cautions.id')->toArray();
        }
    }

    public function setCarCautions()
    {
        // 車両取扱
        if($this->car_caution_ids) {
            $carCautionNames = CarCaution::whereIn('id', $this->car_caution_ids)->pluck('name')->toArray();
            $this->carCautions = implode(', ',  $carCautionNames);
        } else {
            $this->carCautions = '';
        }
    }

    public function handleGoodsAndTotals()
    {
        parent::handleGoodsAndTotals();

        // TODO クーポンの処理
    }
}
