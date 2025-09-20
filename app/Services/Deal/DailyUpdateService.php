<?php
namespace App\Services\Deal;

use App\Enums\DealStatus;
use App\Models\Deal;
use App\Models\SystemDate;
use App\Services\Deal\ExtraPaymentManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyUpdateService
{
    /**
     * 日次更新処理を実行
     * システム日付の更新と、それに伴う保留車両の料金再計算を行う
     *
     * @param Carbon|null $date 指定する日付
     * @param int|null $days 進める日数
     * @return array{newSysDate: SystemDate, overDueDealsCount: int, updatedCount: int, isNewDate: bool}
     * @throws \Throwable
     */
    public function execute(?Carbon $date = null, ?int $days = null): array
    {
        return DB::transaction(function () use ($date, $days) {
            $newSysDate = null;
            $isNewDate = false;

            if ($date) {
                $newSysDate = SystemDate::setDate($date);
            } elseif ($days !== null) {
                $newSysDate = SystemDate::incrementDays($days);
            } else {
                $sysDate = SystemDate::latest()->first();
                if (!$sysDate) {
                    $newSysDate = SystemDate::setDate(Carbon::today());
                    $isNewDate = true;
                } else {
                    $newSysDate = SystemDate::incrementDays(1);
                }
            }

            $overDueDeals = $this->getTargetData();
            $updatedCount = 0;
            foreach($overDueDeals as $deal) {
                $manager = new ExtraPaymentManager($deal);
                $manager->recalcDealPricesWithExtraPayment();
                $updatedCount++;
            }

            return [
                'newSysDate' => $newSysDate,
                'overDueDealsCount' => $overDueDeals->count(),
                'updatedCount' => $updatedCount,
                'isNewDate' => $isNewDate,
            ];
        });
    }

    private function getTargetData()
    {
        $systemDate = SystemDate::latest()->first();
        if(!$systemDate) {
            throw new \ErrorException('システム日付からデータを取得できませんでした。');
        }
        // 対象事業所の入庫済みで未出庫の取引のうち、出庫予定日を過ぎているか、延長発生しているものを取得
        return Deal::query()
            // ->where('id', 155) // テスト用
            ->where('office_id', config('const.commons.office_id'))
            ->where('status', DealStatus::LOADED->value)
            ->where(function ($q) use($systemDate) {
                $q->whereDate('unload_date_plan', '<', $systemDate->system_date)
                  ->orWhere('overdue', true);
            })
            ->orderBy('deals.id', 'asc')
            ->get();
    }
}
