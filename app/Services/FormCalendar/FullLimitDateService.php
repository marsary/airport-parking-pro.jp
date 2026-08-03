<?php

namespace App\Services\FormCalendar;

use App\Enums\LimitOverStatus;
use App\Models\FullLimitSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class FullLimitDateService
{
    /**
     * @var Collection<string, FullLimitSetting>
     */
    private Collection $dateLimits;
    private Carbon $startDate;
    private Carbon $endDate;
    private CarbonPeriod $period;


    public function __construct(
        Carbon $startDate,
        Carbon $endDate
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = CarbonPeriod::create($startDate, $endDate);

        $this->dateLimits = FullLimitSetting::query()
            ->where('office_id', config('const.commons.office_id'))
            ->whereBetween('target_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->get()
            ->keyBy(function (FullLimitSetting $item) {
                return Carbon::parse($item->target_date)->toDateString();
            });
    }

    /**
     * 指定日の制限情報を取得
     */
    public function getDateLimit(Carbon $date): array
    {
        /** @var FullLimitSetting|null $limit */
        $limit = $this->dateLimits->get($date->toDateString());

        if (!$limit) {
            return [
                'load_date' => LimitOverStatus::VACANT->label(),
                'unload_date' => LimitOverStatus::VACANT->label(),
                'cross_time' => LimitOverStatus::VACANT->label(),
                'canCheckIn' => true,
                'canCheckOut' => true,
                'canCrossTime' => true,
            ];
        }

        $load = $limit->limitOverStatus(FullLimitSetting::LOAD_LIMIT_SYMBOL);
        $unload = $limit->limitOverStatus(FullLimitSetting::UNLOAD_LIMIT_SYMBOL);
        $cross = $limit->limitOverStatus(FullLimitSetting::CROSS_TIME_SYMBOL);

        return [
            'load_date' => $load->label(),
            'unload_date' => $unload->label(),
            'cross_time' => $cross->label(),
            'canCheckIn' => $load !== LimitOverStatus::FULL,
            'canCheckOut' => $unload !== LimitOverStatus::FULL,
            'canCrossTime' => $cross !== LimitOverStatus::FULL,
        ];
    }

    /**
     * カレンダー表示用データ
     */
    public function getCalendar(): array
    {
        $results = [];

        foreach ($this->period as $date) {
            $results[$date->toDateString()] = $this->getDateLimit($date);
        }

        return $results;
    }

    /**
     * 予約可能判定
     */
    public function canReserve(): ReserveCheckResult
    {
        foreach ($this->period as $date) {
            $limit = $this->getDateLimit($date);

            if ($date->isSameDay($this->startDate)) {
                if (!$limit['canCheckIn']) {
                    return new ReserveCheckResult(
                        false,
                        $date->format('Y/m/d').'は入庫できません。'
                    );
                }
                continue;
            }

            if ($date->isSameDay($this->endDate)) {
                if (!$limit['canCheckOut']) {
                    return new ReserveCheckResult(
                        false,
                        $date->format('Y/m/d').'は出庫できません。'
                    );
                }
                continue;
            }

            if (!$limit['canCrossTime']) {
                return new ReserveCheckResult(
                    false,
                    '満車のため、' . $date->format('Y/m/d').'をまたぐ予約はできません。'
                );
            }
        }

        return new ReserveCheckResult(true);
    }
}
