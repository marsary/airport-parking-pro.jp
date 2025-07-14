<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGood extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_id',
        'good_category_id',
        'deal_good_id',
        'name',
        'price',
        'tax',
        'tax_type',
        'num',
        'total_price',
        'total_tax',
        'created_by',
        'updated_by',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function goodCategory()
    {
        return $this->belongsTo(GoodCategory::class);
    }

}
