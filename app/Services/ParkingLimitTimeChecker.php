<?php
namespace App\Services;

use App\Enums\DealStatus;
use App\Enums\LimitOverStatus;
use App\Models\Deal;
use App\Models\ParkingLimit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ParkingLimitTimeChecker
{
    private $loadDate;
    /** @var ParkingLimit|null */
    public $defaultLimit;
    /** @var ParkingLimit|null */
    public $dateLimit;
    public $intervals;
    public $range = [];
    public $results = [];

    function __construct(Carbon $loadDate)
    {
        $this->loadDate = $loadDate;
        $this->intervals = CarbonPeriod::since("00:00")->hours(1)->until("23:00", true)->toArray();
        foreach($this->intervals as $interval){
            $this->range[$interval->format("H")] = ['00' => 0, '15' => 0, '30' => 0, '45' => 0];
        }

        $loadCountsByQuarterHour = Deal::select([
            DB::raw('FROM_UNIXTIME(FLOOR( UNIX_TIMESTAMP(load_time)/900 ) * 900, "%H:%i") AS quaterhour'),
            DB::raw('COUNT(id) AS count'),
            // DB::raw('ANY_VALUE(HOUR(load_time)) as `hour`'),
        ])
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('status', DealStatus::UNLOADED->value)
            ->where("load_date", $this->loadDate)
            ->groupBy('quaterhour')
            ->orderBy('quaterhour', 'ASC')
            ->get();

        foreach($loadCountsByQuarterHour as $loadCount){
            [$hour, $min] = explode(':', $loadCount->quaterhour);
            $this->range[$hour][$min] += $loadCount->count;
        }


        $this->defaultLimit = ParkingLimit::where('office_id', config('const.commons.office_id'))
            ->whereNull('target_date')
            ->first();
        $this->dateLimit = ParkingLimit::where('office_id', config('const.commons.office_id'))
            ->whereDate('target_date', $loadDate->toDateString())
            ->whereNotNull('target_date')
            ->first();
    }

    public function checkLoadHours()
    {
        foreach ($this->range as $hour => $hourCounts) {
            $this->results[(int) $hour] = $this->checkLoadTimeCounts($hourCounts);
        }

        return $this->results;
    }

    private function checkLoadTimeCounts($hourCounts)
    {
        $qurterResults = ['00' => LimitOverStatus::VACANT, '15' => LimitOverStatus::VACANT, '30' => LimitOverStatus::VACANT, '45' => LimitOverStatus::VACANT];
        $hourVacant = LimitOverStatus::VACANT;
        if($this->dateLimit) {
            foreach ($hourCounts as $qurterHour => $count) {
                $qurterResults[$qurterHour] = $this->dateLimit->isLimitOver(limitType:ParkingLimit::LOAD_TIME_LIMIT, countByQuarterhour :$count);
                if($qurterResults[$qurterHour] == LimitOverStatus::FULL) {
                    $hourVacant = LimitOverStatus::FULL;
                }
            }
        } else {
            foreach ($hourCounts as $qurterHour => $count) {
                $qurterResults[$qurterHour] = $this->defaultLimit->isLimitOver(limitType:ParkingLimit::LOAD_TIME_LIMIT, countByQuarterhour:$count);
                if($qurterResults[$qurterHour] == LimitOverStatus::FULL) {
                    $hourVacant = LimitOverStatus::FULL;
                }
            }
        }

        return ['hourVacant' => $hourVacant, 'qurterResults' =>$qurterResults];
    }
}
