<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberCar extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'member_id',
        'car_id',
        'car_color_id',
        'number',
        'default_flg',
        'created_by',
        'updated_by',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
