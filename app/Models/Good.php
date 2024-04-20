<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
