<?php

namespace App\Models;

use App\Casts\TimeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'agency_id',
        'agency_name',
        'receipt_code',
        'member_code',
        'deal_id',
        'reserve_name',
        'reserve_name_kana',
        'load_date',
        'unload_date',
        'unload_date_plan',
        'unload_time_plan',
        'num_days',
        'num_days_plan',
        'airline_name',
        'dep_airport_name',
        'flight_name',
        'arrive_date',
        'arrive_time',
        'car_name',
        'car_maker_name',
        'car_color_name',
        'car_number',
        'dt_price_load',
        'price',
        'base_price',
        'pay_not_real',
        'has_voucher',
        'coupon_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'load_date' => 'date',
            'unload_date' => 'date',
            'unload_date_plan' => 'date',
            'unload_time_plan' => TimeCast::class,
            'arrive_date' => 'date',
            'arrive_time' => TimeCast::class,
        ];
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
