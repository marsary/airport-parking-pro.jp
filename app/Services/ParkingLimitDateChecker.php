<?php
namespace App\Services;

use App\Enums\DealStatus;
use App\Models\Deal;
use App\Models\ParkingLimit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ParkingLimitDateChecker
{
    private $startDate;
    private $endDate;
    /** @var ParkingLimit */
    public $defaultLimit;
    /** @var Collection */
    public $dateLimits;
    public $parkingNum;
    public $period;
    public $range = [];
    public $results = [];

    function __construct(Carbon $startDate,Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = CarbonPeriod::create($startDate, $endDate);
        foreach($this->period as $date){
            $this->range[$date->format("Y-m-d")] = ['load_count' => 0, 'unload_count' => 0];
        }

        $this->defaultLimit = ParkingLimit::where('office_id', config('const.commons.office_id'))
            ->whereNull('target_date')
            ->first();
        $this->dateLimits = ParkingLimit::where('office_id', config('const.commons.office_id'))
            ->whereDate('target_date','>=', $startDate->toDateString())
            ->whereDate('target_date','<', $endDate->toDateString())
            ->whereNotNull('target_date')
            ->get();

        $this->parkingNum = Deal::whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('status', DealStatus::UNLOADED->value)
            ->whereDate('load_date','<', $this->startDate)
            ->count();

        $loadCountsByDay = Deal::select([
                'load_date',
                DB::raw('COUNT(id) AS count'),
            ])
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('status', DealStatus::UNLOADED->value)
            ->whereBetween("load_date", [$this->startDate, $this->endDate])
            ->groupBy('load_date')
            ->orderBy('load_date', 'ASC')
            ->get();

        foreach($loadCountsByDay as $loadCount){
            $this->range[$loadCount->load_date->format("Y-m-d")]['load_count'] = $loadCount->count;
        }

        $unloadCountsByDay = Deal::select([
                'unload_date_plan',
                DB::raw('COUNT(id) AS count'),
            ])
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('status', DealStatus::UNLOADED->value)
            ->whereBetween("unload_date_plan", [$this->startDate, $this->endDate])
            ->groupBy('unload_date_plan')
            ->orderBy('unload_date_plan', 'ASC')
            ->get();

        foreach($unloadCountsByDay as $unloadCount){
            $this->range[$unloadCount->unload_date_plan->format("Y-m-d")]['unload_count'] = $unloadCount->count;
        }

        $unloadedCountsByDay = Deal::select([
                'unload_date',
                DB::raw('COUNT(id) AS count'),
            ])
            ->where('status', DealStatus::UNLOADED->value)
            ->whereBetween("unload_date", [$this->startDate, $this->endDate])
            ->groupBy('unload_date')
            ->orderBy('unload_date', 'ASC')
            ->get();

        foreach($unloadedCountsByDay as $unloadedCount){
            $this->range[$unloadedCount->unload_date->format("Y-m-d")]['unload_count'] += $unloadedCount->count;
        }
    }

    public function checkLoadDate()
    {
        foreach ($this->period as $date) {
            $this->results[$date->format('Y-m-d')] = $this->checkLoadDateAt($date);
        }

        return $this->results;
    }

    private function checkLoadDateAt($targetDate)
    {
        $counts = $this->range[$targetDate->format('Y-m-d')];
        $this->parkingNum += $counts['load_count'];
        $this->parkingNum -= $counts['unload_count'];
        /** @var ParkingLimit $dateLimit */
        $dateLimit = $this->dateLimits->filter(function($limit) use($targetDate) {
            return $limit->target_date == $targetDate;
        })->first();

        if($dateLimit) {
            return $dateLimit->isLimitOver(ParkingLimit::LOAD_LIMIT, $this->parkingNum, $counts['load_count']);
        }

        return $this->defaultLimit->isLimitOver(ParkingLimit::LOAD_LIMIT, $this->parkingNum, $counts['load_count']);
    }


    public function checkUnloadDate()
    {
        foreach ($this->period as $date) {
            $this->results[$date->format('Y-m-d')] = $this->checkUnloadDateAt($date);
        }

        return $this->results;
    }

    private function checkUnloadDateAt($targetDate)
    {
        $counts = $this->range[$targetDate->format('Y-m-d')];
        /** @var ParkingLimit $dateLimit */
        $dateLimit = $this->dateLimits->filter(function($limit) use($targetDate) {
            return $limit->target_date == $targetDate;
        })->first();

        if($dateLimit) {
            return $dateLimit->isLimitOver(limitType:ParkingLimit::UNLOAD_LIMIT, unloadCount:$counts['unload_count']);
        }

        return $this->defaultLimit->isLimitOver(limitType:ParkingLimit::UNLOAD_LIMIT, unloadCount:$counts['unload_count']);
    }
}
