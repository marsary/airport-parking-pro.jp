<?php

namespace App\Models;

use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
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
        'type',
        'memo',
        'multiple',
    ];

    public static function getIdNameMapGroupedByCategory()
    {
        $map = [];
        foreach (self::where('office_id', config('const.commons.office_id'))->get() as $paymentMethod) {
            $category = PaymentMethodType::tryFrom($paymentMethod->type)?->symbol();
            if(!isset($map[$category])) {
                $map[$category] = [];
            }
            $map[$category][] = $paymentMethod;
        }
        return $map;
    }

    public static function getByName(string $name)
    {
        return self::where('name', $name)->first();
    }
}
