<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlySalesTarget extends Model
{
    use HasFactory, SoftDeletes;

    const TOTAL_SALES_ORDER = 1;
    const PARKING_FEE = 2;
    const GOOD_CATEGORY_ORDERS = [3, 4, 5];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'target_month',
        'order',
        'good_category_id',
        'sales_target',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function goodCategory()
    {
        return $this->belongsTo(GoodCategory::class);
    }

    public function salesTargetPerDay(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $targetMonth = Carbon::createFromFormat('Ym', $attributes['target_month']);
                $daysInMonth = $targetMonth->daysInMonth();
                return floor($attributes['sales_target'] / $daysInMonth);
            }
        );
    }
}
