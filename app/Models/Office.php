<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'airport_id',
        'receipt_issuer',
        'zip',
        'receipt_address',
        'receipt_tel',
        'email',
        'max_car_num',
        'start_time',
        'end_time',
    ];

    public function airport()
    {
        return $this->belongsTo(Airport::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function agencyRecords()
    {
        return $this->hasMany(AgencyRecord::class);
    }
}
