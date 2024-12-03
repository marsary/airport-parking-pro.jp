<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicPricing extends Model
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
        'p10',
        'p20',
        'p30',
        'p40',
        'p50',
        'p60',
        'p70',
        'p80',
        'p90',
        'p100',
        'p110',
        'p120',
        'p130',
        'p131',
    ];
}
