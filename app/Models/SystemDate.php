<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemDate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'system_date',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'system_date' => 'date',
        ];
    }

    /**
     * システム日付を進める
     * 新規の場合は今日の日付から開始し、指定された日数を進める
     *
     * @param int $days 進める日数 (デフォルトは1)
     *
     * @return self
     */
    public static function incrementDays($days = 1)
    {
        // id=1 のレコードを対象とし、存在しない場合は新しいインスタンスを作成します。
        $sysDate = self::firstOrNew(['id' => 1]);

        if ($sysDate->exists) {
            // 既存レコードの日付を指定された日数進める
            $sysDate->system_date = $sysDate->system_date->addDays($days);
        } else {
            // レコードが存在しない場合、今日の日付から開始して指定された日数進める
            $currentDate = Carbon::today();
            $newDate = $currentDate->addDays($days);
            $sysDate->system_date = $newDate;
        }

        $sysDate->save();

        return $sysDate;
    }

    /**
     * システム日付を指定の日付に設定する
     *
     * @param Carbon $date
     * @return self
     */
    public static function setDate(Carbon $date)
    {
        $sysDate = self::firstOrNew(['id' => 1]);
        $sysDate->system_date = $date->startOfDay();
        $sysDate->save();
        return $sysDate;
    }
}
