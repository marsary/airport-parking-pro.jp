<?php

namespace App\Models;

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

    public function dealGoods()
    {
        return $this->hasMany(DealGood::class);
    }
}