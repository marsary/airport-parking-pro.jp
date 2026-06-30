<?php
namespace App\Http\Controllers\Form\Forms;

use App\Enums\DealStatus;
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
        $this->reserve_date = Carbon::now();
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

    public function setRemarkForOptionSelect()
    {
        $optionValues = ['H', 'W', 'メ希'];
        $remarkParts = preg_split('/\s+/u', trim((string) $this->remarks));
        $filteredRemarkParts = array_values(array_filter($remarkParts, static fn ($part) => !in_array($part, $optionValues, true)));
        $this->remarks = implode(' ', $filteredRemarkParts);

        $optionSelectData = [];
        if($this->insurance) {
            $optionSelectData[] = 'H';
        }
        if($this->carwash) {
            $optionSelectData[] = 'W';
        }
        if($this->newsletter) {
            $optionSelectData[] = 'メ希';
        }
        if(!empty($optionSelectData)) {
            $this->remarks .= ' ' . implode(' ', $optionSelectData);
        }
    }
}
