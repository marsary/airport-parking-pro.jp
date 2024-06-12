<?php
namespace App\Http\Controllers\Form\Forms;

use App\Http\Controllers\Forms\ReserveFormBase;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class ReserveForm extends ReserveFormBase
{
    public function setMember(Member $member = null)
    {
        parent::setMember($member);

        if($this->member_car_id) {
            // 車両取扱
            $this->carCautions = DB::table('car_cautions')
                ->join('car_caution_member_cars', 'car_cautions.id', '=', 'car_caution_member_cars.car_caution_id')
                ->where('car_caution_member_cars.member_id', $member->id)
                ->where('car_caution_member_cars.office_id', $this->office_id)
                ->where('car_caution_member_cars.member_car_id', $this->member_car_id)
                ->orderBy('car_cautions.sort')
                ->pluck('car_cautions.name')->implode('name', ', ');
        }
    }

    public function handleGoodsAndTotals()
    {
        parent::handleGoodsAndTotals();

        // TODO クーポンの処理
    }

}
