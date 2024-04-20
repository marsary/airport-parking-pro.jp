<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'zip',
        'address1',
        'address2',
        'tel',
        'keyword',
        'department',
        'position',
        'person',
        'email',
        'payment_site',
        'payment_destination',
        'memo',
        'receipt_issue',
    ];


}
