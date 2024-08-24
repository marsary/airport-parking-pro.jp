<?php

namespace App\Models;

use App\Enums\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Good extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'good_category_id',
        'status',
        'name',
        'abbreviation',
        'price',
        'tax_type',
        'one_day_flg',
        'start_date',
        'end_date',
        'sort',
        'memo',
        'created_by',
        'updated_by',
    ];

    public function dealGoods()
    {
        return $this->hasMany(DealGood::class);
    }

    public function tax(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return roundTax(TaxType::tryFrom($attributes['tax_type'])->rate() * $attributes['price']);
            }
        );
    }

    public function goodCategory()
    {
        return $this->belongsTo(GoodCategory::class);
    }
}
