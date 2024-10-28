<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Agency extends Model
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
        'zip',
        'address1',
        'address2',
        'tel',
        'keyword',
        'branch',
        'department',
        'position',
        'person',
        'email',
        'payment_site',
        'payment_destination',
        'memo',
        'receipt_issue',
        'monthly_fixed_cost_flag',
        'monthly_fixed_cost',
        'incentive_flag',
        'incentive',
        'banner_comment_set',
        'title_set',
        'logo_image',
        'campaign_image',
    ];

    public function address(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $attributes['address1'] . $attributes['address2'];

            }
        );
    }
}
