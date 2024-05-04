<?php

namespace App\Models;

use App\Enums\LimitOverStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SebastianBergmann\CodeCoverage\Report\Thresholds;

class ParkingLimit extends Model
{
    use HasFactory, SoftDeletes;

    const LOAD_LIMIT = 'load_date';
    const UNLOAD_LIMIT = 'unload_date';
    const LOAD_TIME_LIMIT = 'load_time';

    const THRESHOLD = 0.7;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'target_date',
        'max_parking_num',
        'load_limit',
        'unload_limit',
        'at_closing_time',
        'per_fifteen_munites',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'target_date' => 'datetime',
    ];

    // 事業所単位、日付単位で
    // 最大駐車数、入庫上限、出庫上限、駐車上限、15分当たりの入庫上限を超えているか
    public function isLimitOver($limitType, $parkingNum = null, $loadCount = null, $unloadCount = null, $countByQuarterhour = null):LimitOverStatus
    {
        switch ($limitType) {
            case self::LOAD_LIMIT:
                // 最大駐車数
                if(isset($parkingNum) && $parkingNum >= $this->max_parking_num) {
                    return LimitOverStatus::FULL;
                }
                if(isset($parkingNum) && $parkingNum >= $this->max_parking_num * self::THRESHOLD) {
                    return LimitOverStatus::HALF_FILLED;
                }
                // 入庫上限
                if(isset($loadCount) && $loadCount >= $this->load_limit) {
                    return LimitOverStatus::FULL;
                }
                if(isset($loadCount) && $loadCount >= $this->load_limit * self::THRESHOLD) {
                    return LimitOverStatus::HALF_FILLED;
                }
                break;
            case self::UNLOAD_LIMIT:
                // 出庫上限
                if(isset($unloadCount) && $unloadCount >= $this->unload_limit) {
                    return LimitOverStatus::FULL;
                }
                if(isset($unloadCount) && $unloadCount >= $this->unload_limit * self::THRESHOLD) {
                    return LimitOverStatus::HALF_FILLED;
                }
                break;
            case self::LOAD_TIME_LIMIT:
                // 15分当たりの入庫上限
                if(isset($countByQuarterhour)) {
                    if($countByQuarterhour >= $this->per_fifteen_munites) {
                        return LimitOverStatus::FULL;
                    }
                }
                break;
            default:
                break;
        }

        return LimitOverStatus::VACANT;
    }
}
