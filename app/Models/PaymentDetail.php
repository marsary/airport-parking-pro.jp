<?php

namespace App\Models;

use App\Enums\PaymentMethod\AdjustmentType;
use App\Enums\PaymentMethod\DiscountType;
use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_id',
        'payment_method_id',
        'total_price',
        'coupon_id',
        'discount_type',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function paymentMethodCategory()
    {
        return PaymentMethodType::tryFrom($this->paymentMethod->type)?->symbol();
    }

    public function paymentMethodDiscountType()
    {
        return DiscountType::tryFrom($this->discount_type);
    }

    public function paymentMethodAdjustmentType()
    {
        return AdjustmentType::tryFrom($this->discount_type);
    }

    public static function getDiscountTypeMap()
    {
        $map = [];
        foreach (DiscountType::cases() as $discountType) {
            $map['discount'][$discountType->label()] = $discountType->value;
        }
        foreach (AdjustmentType::cases() as $adjustmentType) {
            $map['adjustment'][$adjustmentType->label()] = $adjustmentType->value;
        }
        return $map;
    }
}
