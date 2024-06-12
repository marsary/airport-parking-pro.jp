<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_code',
        'payment_date',
        'cash_register_id',
        'office_id',
        'deal_id',
        'user_id',
        'user_name',
        'member_id',
        'load_date',
        'unload_date_plan',
        'unload_date',
        'days',
        'price',
        'goods_total_price',
        'total_price',
        'total_tax',
        'demand_price',
        'total_pay',
        'cash_enter',
        'cash_change',
        'cash_add',
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
            'payment_date' => 'datetime',
            'load_date' => 'date',
            'unload_date_plan' => 'date',
            'unload_date' => 'date',
        ];
    }

    public function paymentGoods()
    {
        return $this->hasMany(PaymentGood::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

}
