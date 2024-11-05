<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'name',
        'code',
        'discount_amount',
        'discount_type',
        'good_category_id',
        'limit_flg',
        'combination_flg',
        'start_date',
        'end_date',
        'memo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
    * 配列/JSONシリアル化の日付を準備
    *
    * @param  \DateTimeInterface  $date
    * @return string
    */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function goodCategory()
    {
        return $this->belongsTo(GoodCategory::class);
    }
}
