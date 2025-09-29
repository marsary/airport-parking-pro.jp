<?php
namespace App\Http\Controllers\Forms;

use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Helpers\StdObject;
use App\Models\Car;
use App\Models\Coupon;
use App\Models\Good;
use App\Models\Member;
use App\Models\MemberCar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReserveFormBase extends StdObject
{
    public $registerMember;

    /** @var Member */
    public $member;

    public $carCautions;

    /** @var array<int,Good> key が goodのID */
    protected $goodsMap;

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
    public $visit_date_plan;
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

    public $good_ids = [];
    public $modal_good_ids = [];
    public $good_nums = [];
    public $modal_good_nums = [];
    public $coupon_ids = [];
    public $coupon_code;
    /** @var Coupon */
    public $coupons = [];

    public $airline_id;
    public $flight_no;
    public $arrive_date;

    public $total_tax_8;
    public $total_tax_10;

    public $tax_free;

    protected $cast = [
        'load_date' => 'date',
        'unload_date_plan' => 'date',
        'visit_date_plan' => 'date',
        'arrive_date' => 'date',
        'arrive_time' => 'time',
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
        $this->setRelatedData();
    }

    protected function setRelatedData()
    {
        if($this->coupon_ids && !$this->coupons) {
            $this->coupons = Coupon::whereIn('id', $this->coupon_ids)->get();
        }
        if($this->car_id && !$this->size_type) {
            $this->size_type = Car::where('id', $this->car_id)->first()?->size_type;
        }
        if($this->total_price < $this->price) {
            $this->total_tax = $this->tax;
            $this->total_price = $this->price;
        }
    }

    public function fillMember()
    {
        if($this->registerMember || !$this->member) {
            $this->member = new Member();
            $this->member->member_code = Str::ulid();
            $this->member->office_id = $this->office_id;
        }
        $this->member->fill([
            'name' => $this->name,
            'kana' => $this->kana,
            'zip' => $this->zip,
            'tel' => $this->tel,
            'email' => $this->email,
        ]);
    }

    public function setMember(Member $member = null)
    {
        if($member === null) {
            return;
        }
        $this->member_id = $member->id;
        $this->member = $member;
        $this->fill($member);

        $memberCar = MemberCar::with('car.carMaker')->where('member_id', $member->id)
            ->where('office_id', $this->office_id)
            ->orderBy('default_flg', 'desc')
            ->orderBy('updated_at', 'desc')
            ->first();

        if($memberCar) {
            $this->car_maker_id = $memberCar->car->carMaker->id;
            $this->car_id = $memberCar->car_id;
            $this->car_color_id = $memberCar->car_color_id;
            $this->car_number = $memberCar->number;
            $this->member_car_id = $memberCar->id;
        }
    }

    protected function clearTotals()
    {
        $this->total_tax_8 = 0;
        $this->total_tax_10 = 0;
        $this->total_price = 0;
        $this->total_tax = 0;
        $this->tax_free = 0;
    }

    public function handleGoodsAndTotals()
    {
        $this->clearTotals();

        $this->total_tax = $this->tax;
        $this->total_price = $this->price;
        $this->addToEachTaxType($this->tax, TaxType::TEN_PERCENT->value);

        $numOfEachGood = 1;
        $this->dealGoodData = [];
        if($this->good_ids) {
            $this->goodsMap = getKeyMapCollection(Good::whereIn('id', $this->good_ids)->get());
            foreach ($this->good_ids as $goodId) {
                $good = $this->goodsMap[$goodId];
                $numOfEachGood = isset($this->good_nums[$goodId]) ? $this->good_nums[$goodId]: 0;

                if($numOfEachGood == 0) {
                    continue;
                }

                $goodTotalPrice = $good->price * $numOfEachGood;
                $goodTotalTax = roundTax((TaxType::tryFrom($good->tax_type)?->rate() ?? 0) * $goodTotalPrice);
                $this->dealGoodData[] = [
                    'good_id' => $good->id,
                    'num' => $numOfEachGood,
                    'price' => $good->price,
                    'total_price' => $goodTotalPrice,
                    'total_tax' => $goodTotalTax,
                    'name' => $good->name,
                    'tax_type_label' => $this->getTaxTypeLabel($good->tax_type),
                ];

                $this->total_tax += $goodTotalTax;
                $this->total_price += $good->price * $numOfEachGood;
                $this->addToEachTaxType($goodTotalTax, $good->tax_type);
                $this->addToTaxFree($goodTotalPrice, $good->tax_type);
            }
        }
    }

    public function getTaxTypeLabel($taxType)
    {
        return match ($taxType) {
            TaxType::EIGHT_PERCENT->value => '税別' . TaxType::EIGHT_PERCENT->label(),
            TaxType::TEN_PERCENT->value => '税別' . TaxType::TEN_PERCENT->label(),
            TaxType::EXEMPT->value => TaxType::EXEMPT->label(),
            default => "",
        };
    }

    protected function addToEachTaxType($tax, $taxType)
    {
        if ($taxType == TaxType::EIGHT_PERCENT->value) {
            $this->total_tax_8 += $tax;
        } elseif ($taxType == TaxType::TEN_PERCENT->value) {
            $this->total_tax_10 += $tax;
        }
    }

    protected function addToTaxFree($price, $taxType)
    {
        if($taxType == TaxType::EXEMPT->value) {
            $this->tax_free += $price;
        }
    }

    protected function cast($property, $value)
    {
        $method = $this->cast[$property];
        return match ($method) {
            'date' => Carbon::parse($value),
            'time' => Carbon::parse($value)->format('H:i:s'),
            default => $value,
        };
    }

    public function totalCharge()
    {
        return $this->total_price + $this->total_tax;
    }

    public function pricePerDay()
    {
        if(empty($this->num_days)) {
            return $this->price;
        }
        return (int) ($this->price / $this->num_days);
    }
}
