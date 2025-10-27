<?php

namespace App\Console\Commands;

use App\Models\DailySalesResult;
use App\Models\Deal;
use App\Models\MonthlySalesTarget;
use App\Models\SystemDate;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SaveDailySalesResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-dailysales-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '月間売上目標で設定した項目に対して、日次の売上実績を保存するバッチ処理。対象はシステム日付の取引データ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('日次売上実績データ作成バッチを実行します。');

        try {
            // システム日付からデータを取得する。
            $sysDate = SystemDate::latest()->first();

            if(!$sysDate) {
                throw new ErrorException('システム日付からデータを取得できませんでした。');
            }

            // システム日付を対象日付とする
            /** @var Carbon $targetDate */
            $targetDate = $sysDate->system_date;
            $officeId = config('const.commons.office_id');

            $goodCategoryOrderMap = $this->getGoodCategoryOrderMap($officeId, $targetDate);

            // 対象データを取得
            $deals = $this->getTargetData($targetDate, $officeId);
            $this->info('対象データ件数: ' . $deals->count() . '件');

            // order=1: 総売上
            $totalSales = $deals->sum(function ($deal) {
                return $deal->total_price + $deal->total_tax;
            });
            $this->saveDailySalesResult($officeId, $targetDate, DailySalesResult::TOTAL_SALES_ORDER, $totalSales);

            // order=2: 駐車料金売上
            $parkingFeeSales = $deals->sum(function ($deal) {
                return $deal->price + $deal->tax;
            });
            $this->saveDailySalesResult($officeId, $targetDate, DailySalesResult::PARKING_FEE, $parkingFeeSales);

            // order=3-6: 商品カテゴリ別売上
            foreach (DailySalesResult::GOOD_CATEGORY_ORDERS as $order) {
                $goodCategoryId = $goodCategoryOrderMap->get($order);
                if (!$goodCategoryId) {
                    continue;
                }

                $categorySales = 0;
                foreach ($deals as $deal) {
                    foreach ($deal->dealGoods as $dealGood) {
                        if ($dealGood->good->good_category_id == $goodCategoryId) {
                            $categorySales += $dealGood->total_price + $dealGood->total_tax;
                        }
                    }
                }
                $this->saveDailySalesResult($officeId, $targetDate, $order, $categorySales, $goodCategoryId);
            }

            $this->info('日次売上実績データの保存が完了しました。');

        } catch (\Throwable $th) {
            Log::error('日次売上実績データ作成バッチでエラーが発生しました: ' . $th->getMessage());
            $this->error('日次売上実績データ作成バッチでエラーが発生しました: ' . $th->getMessage());
        }
    }

    /**
     * 商品カテゴリとorderの対応マップを取得する
     *
     * @param int $officeId
     * @param Carbon $targetDate
     * @return \Illuminate\Support\Collection
     */
    private function getGoodCategoryOrderMap(int $officeId, Carbon $targetDate)
    {
        $targetMonthStr = $targetDate->format('Ym');

        // 今月の月間売上目標から商品カテゴリの対応を取得
        $goodCategoryOrderMap = MonthlySalesTarget::where('office_id', $officeId)
            ->where('target_month', $targetMonthStr)
            ->whereIn('order', DailySalesResult::GOOD_CATEGORY_ORDERS)
            ->pluck('good_category_id', 'order');

        return $goodCategoryOrderMap;
    }

    /**
     * 対象となる取引データを取得する
     * @param Carbon $targetDate
     * @param int $officeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTargetData(Carbon $targetDate, int $officeId)
    {
        return Deal::where('office_id', $officeId)
            ->whereDate('load_date', $targetDate)
            ->has('payment')
            ->with(['dealGoods.good'])
            ->get();
    }

    /**
     * 日次売上実績を保存する
     * @param int $officeId
     * @param Carbon $targetDate
     * @param int $order
     * @param int $sales
     * @param int|null $goodCategoryId
     */
    private function saveDailySalesResult(int $officeId, Carbon $targetDate, int $order, int $sales, ?int $goodCategoryId = null)
    {
        $dailySalesResult = DailySalesResult::where('office_id', $officeId)
            ->where('target_date', $targetDate)
            ->where('order', $order)
            ->first();

        if ($dailySalesResult) {
            $dailySalesResult->sales_target = $sales;
            $dailySalesResult->good_category_id = $goodCategoryId;
            $dailySalesResult->save();
        } else {
            DailySalesResult::create([
                'office_id' => $officeId,
                'target_date' => $targetDate,
                'order' => $order,
                'sales_target' => $sales,
                'good_category_id' => $goodCategoryId
            ]);
        }
    }
}
