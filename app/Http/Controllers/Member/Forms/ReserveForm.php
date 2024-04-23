<?php
namespace App\Http\Controllers\Member\Forms;

use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Helpers\StdObject;
use App\Models\CarCautionMemberCar;
use App\Models\Good;
use App\Models\Member;
use App\Models\MemberCar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReserveForm extends StdObject
{
    /** @var Member */
    public $member;

    public $carCautions;

    /** @var array<int,Good> key が goodのID */
    private $goodsMap;

    /** @var array<array<string,mixed>> */
    public $dealGoodData = [];

    public $member_id;
    public $office_id;
    public $agency_id;
    public $agency_code;
    public $status;
    public $reserve_code;
    public $receipt_code;
    public $reserve_date;
    public $load_date;
    public $load_time;
    public $unload_date_plan;
    public $unload_time_plan;
    public $arrival_flg;
    // public $unload_date;
    // public $unload_time;
    public $num_days;
    public $num_members;
    public $name;
    public $kana;
    public $zip;
    public $tel;
    public $email;
    public $dsc_rate;
    public $price;
    public $tax;
    public $total_price;
    public $total_tax;
    public $dep_flight_id;
    public $arr_flight_id;
    public $member_car_id;
    public $car_maker_id;
    public $car_id;
    public $car_color_id;
    public $car_number;
    public $size_type;
    // public $billing_type;
    public $receipt_address;
    public $reserve_memo;
    public $reception_memo;
    public $remarks;

    protected $cast = [
        'load_date' => 'date',
        'unload_date_plan' => 'date',
        'arrive_date' => 'date',
        'reserve_date' => 'date',
    ];

    function __construct()
    {
        $this->office_id = config('const.commons.office_id');
        $this->status = DealStatus::NOT_LOADED->value;
        $this->reserve_date = Carbon::now();
        $this->reserve_code = Str::ulid();
    }


    public function fill($data = [])
    {
        if($data instanceof Model) {
            $data = $data->toArray();
        }
        if (!empty($data)) {
            foreach ($data as $property => $value) {
                if(isset($this->cast[$property])) {
                    $value = $this->cast($property, $value);
                }
                $this->{$property} = $value;
            }
        }
    }

    public function setMember(Member $member = null)
    {
        if($member === null) {
            return;
        }
        $this->member_id = $member->id;
        $this->member = $member;
        $this->fill($member);
    }


    protected function cast($property, $value)
    {
        $method = $this->cast[$property];
        return match ($method) {
            'date' => Carbon::parse($value),
            default => $value,
        };
    }
}
