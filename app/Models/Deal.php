<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\DealStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'office_id',
        'agency_id',
        'reservation_route',
        'status',
        'reserve_code',
        'receipt_code',
        'reserve_date',
        'load_date',
        'load_time',
        'visit_date_plan',
        'visit_time_plan',
        'unload_date_plan',
        'unload_time_plan',
        'arrival_flg',
        'unload_date',
        'unload_time',
        'num_days',
        'num_members',
        'name',
        'kana',
        'zip',
        'tel',
        'email',
        'dsc_rate',
        'price',
        'tax',
        'total_price',
        'total_tax',
        'dep_flight_id',
        'arr_flight_id',
        'member_car_id',
        'receipt_address',
        'reserve_memo',
        'reception_memo',
        'remarks',
        'created_by',
        'updated_by',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reserve_date' => 'datetime',
            'load_date' => 'date',
            'load_time' => TimeCast::class,
            'visit_time_plan' => TimeCast::class,
            'unload_time_plan' => TimeCast::class,
            'visit_date_plan' => 'date',
            'unload_date_plan' => 'date',
            'unload_date' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status_label'];

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return DealStatus::tryFrom($attributes['status'])?->label();
            }
        );
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function dealGoods()
    {
        return $this->hasMany(DealGood::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function memberCar()
    {
        return $this->belongsTo(MemberCar::class, 'member_car_id');
    }

    public function arrivalFlight()
    {
        return $this->belongsTo(ArrivalFlight::class, 'arr_flight_id');
    }

    public function carCautionMemberCars()
    {
        return $this->hasMany(CarCautionMemberCar::class, 'member_car_id', 'member_car_id');
    }

    public function loadDateTime($format = "Y/m/d")
    {
        $datetimeStr = $this->load_date?->format($format);
        if(!isBlank($this->load_time)) {
            $datetimeStr .=  ' ' . formatDate($this->load_time, 'H:i');
        }
        return $datetimeStr;
    }

    public function dealGoodsTotalPrice()
    {
        $totalPrice = 0;
        if($this->dealGoods()->count() > 0) {
            foreach ($this->dealGoods as $dealGood) {
                $totalPrice += $dealGood->total_price + $dealGood->total_tax;
            }
        }
        return $totalPrice;
    }

    public function carCautions($separator = ', '):string
    {
        $carCautionList = [];
        foreach ($this->carCautionMemberCars as $carCautionMemberCar) {
            $carCautionList[] = $carCautionMemberCar->carCaution->name;
        }

        return implode($separator, $carCautionList);
    }

}
