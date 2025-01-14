<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'status',
        'member_type_id',
        'name',
        'kana',
        'en_name',
        'zip',
        'address1',
        'address2',
        'tel',
        'email',
        'line_id',
        'line_account',
        'line_email',
        'image_url',
        'password',
        'remember_token',
        'used_num',
        'memo',
        'created_by',
        'updated_by',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function memberType()
    {
        return $this->belongsTo(MemberType::class);
    }

    public function memberCars()
    {
        return $this->hasMany(MemberCar::class);
    }

    public function tagMembers()
    {
        return $this->hasMany(TagMember::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getRedirectRoute()
    {
        return 'reserves.entry_date';
    }
}
