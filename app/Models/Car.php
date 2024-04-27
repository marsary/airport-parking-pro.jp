<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_maker_id',
        'name',
        'kana',
        'sort',
        'size_type',
        'memo',
    ];

    public function carMaker()
    {
        return $this->belongsTo(CarMaker::class);
    }

    public function sizeLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return match ($attributes['size_type']) {
                    1 => '普通車',
                    2 => '大型車',
                    default => '不明',
                };
            }
        );
    }
}
