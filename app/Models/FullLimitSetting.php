<?php

namespace App\Models;

use App\Enums\LimitOverStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FullLimitSetting extends Model
{
    use HasFactory, SoftDeletes;

    const LOAD_LIMIT_SYMBOL = 'load_limit_symbol';
    const UNLOAD_LIMIT_SYMBOL = 'unload_limit_symbol';
    const CROSS_TIME_SYMBOL = 'cross_time_symbol';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'target_date',
        'load_limit_symbol',
        'unload_limit_symbol',
        'cross_time_symbol',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'target_date' => 'datetime',
    ];

    public function limitOverStatus(string $limitType):LimitOverStatus
    {
        switch ($limitType) {
            case self::LOAD_LIMIT_SYMBOL:
                return LimitOverStatus::tryFrom($this->load_limit_symbol) ?? LimitOverStatus::VACANT;
            case self::UNLOAD_LIMIT_SYMBOL:
                return LimitOverStatus::tryFrom($this->unload_limit_symbol) ?? LimitOverStatus::VACANT;
            case self::CROSS_TIME_SYMBOL:
                return LimitOverStatus::tryFrom($this->cross_time_symbol) ?? LimitOverStatus::VACANT;
            default:
                break;
        }

        return LimitOverStatus::VACANT;
    }
}
