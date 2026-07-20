<?php
namespace App\Http\Controllers\Form\Forms;

use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Http\Controllers\Forms\ReserveFormBase;
use App\Models\Member;
use App\Models\MemberCar;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReserveForm extends ReserveFormBase
{
    public bool $insurance = true;
    public bool $carwash = true;
    public bool $newsletter = true;


    function __construct()
    {
        $this->office_id = config('const.commons.form_office_id');
        $this->status = DealStatus::NOT_LOADED->value;
        $this->reserve_date = null;
        $this->reserve_code = Str::ulid();
    }

    public function setMember(?Member $member = null)
    {
        if($member === null) {
            return;
        }
        $this->member_id = $member->id;
        $this->member = $member;
        $this->fill($member);

        $memberCar = MemberCar::with('car.carMaker')->where('member_id', $member->id)
            ->where('office_id', config('const.commons.form_office_id'))
            ->orderBy('default_flg', 'desc')
            ->orderBy('updated_at', 'desc')
            ->first();

        if($memberCar) {
            $this->car_maker_id = $memberCar->car?->carMaker->id;
            $this->car_id = $memberCar->car_id;
            $this->car_color_id = $memberCar->car_color_id;
            $this->car_number = $memberCar->number;
            $this->member_car_id = $memberCar->id;
        }
    }

    public function fillMember()
    {
        if(!$this->member) {
            $this->member = new Member();
            $this->member->member_code = Str::ulid();
            $this->member->office_id = config('const.commons.form_office_id');
        }
        $this->member->fill([
            'name' => $this->name,
            'kana' => $this->kana,
            'zip' => $this->zip,
            'tel' => $this->tel,
            'email' => $this->email,
        ]);
    }

    public function handleGoodsAndTotals()
    {
        parent::handleGoodsAndTotals();

        // TODO クーポンの処理
    }


    public function getTaxTypeLabel($taxType)
    {
        return match ($taxType) {
            TaxType::EIGHT_PERCENT->value => '税込' . TaxType::EIGHT_PERCENT->label(),
            TaxType::TEN_PERCENT->value => '税込' . TaxType::TEN_PERCENT->label(),
            TaxType::EXEMPT->value => TaxType::EXEMPT->label(),
            default => "",
        };
    }
}
