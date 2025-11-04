<?php
namespace App\Services\Settings;

use App\Models\DailySalesResult;
use App\Models\MonthlySalesTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlySalesTargetResultsService
{
    private int $year;
    private int $officeId;

    /**
     * @param int $year
     */
    public function __construct(int $year)
    {
        $this->year = $year;
        $this->officeId = myOffice()->id;
    }

    /**
     * 指定された年の全テーブルのデータを生成
     *
     * @return array
     */
    public function generateData(): array
    {
        // 1年分の売上目標と実績をまとめて取得
        $monthlyTargets = $this->getMonthlyTargetsForYear();
        $monthlyResults = $this->getMonthlyResultsForYear();

        $data = [];
        $orders = [
            'total_sales' => MonthlySalesTarget::TOTAL_SALES_ORDER,
            'parking_fee' => MonthlySalesTarget::PARKING_FEE,
            'good_category_1' => 3,
            'good_category_2' => 4,
            'good_category_3' => 5,
            'good_category_4' => 6,
        ];

        foreach ($orders as $key => $order) {
            $data[$key] = $this->buildTableData($order, $monthlyTargets, $monthlyResults);
        }

        return $data;
    }

    /**
     * 指定されたorderのテーブルデータを構築
     *
     * @param int $order
     * @param \Illuminate\Support\Collection $monthlyTargets
     * @param \Illuminate\Support\Collection $monthlyResults
     * @return array
     */
    private function buildTableData(int $order, $monthlyTargets, $monthlyResults): array
    {
        $table = [
            'target' => [],
            'result' => [],
            'difference' => [],
            'achievement_rate' => [],
        ];

        for ($month = 1; $month <= 12; $month++) {
            $targetMonthStr = $this->year . str_pad($month, 2, '0', STR_PAD_LEFT);

            $target = $monthlyTargets->where('target_month', $targetMonthStr)->where('order', $order)->first()?->sales_target;
            $result = $monthlyResults->where('result_month', $targetMonthStr)->where('order', $order)->first()?->total_sales ?? 0;

            $difference = $result - $target;
            $achievementRate = ($target > 0) ? round(($result / $target) * 100) . '%' : '-';

            $table['target'][$month] = $target ?? '-';
            $table['result'][$month] = $result;
            $table['difference'][$month] = $difference;
            $table['achievement_rate'][$month] = $achievementRate;
        }

        return $table;
    }

    /**
     * 指定された年の月間売上目標をすべて取得
     *
     * @return \Illuminate\Support\Collection
     */
    private function getMonthlyTargetsForYear()
    {
        $startMonth = $this->year . '01';
        $endMonth = $this->year . '12';

        return MonthlySalesTarget::where('office_id', $this->officeId)
            ->whereBetween('target_month', [$startMonth, $endMonth])
            ->get();
    }

    /**
     * 指定された年の月間売上実績をすべて取得
     *
     * @return \Illuminate\Support\Collection
     */
    private function getMonthlyResultsForYear()
    {
        $startDate = Carbon::create($this->year, 1, 1)->startOfYear();
        $endDate = Carbon::create($this->year, 12, 31)->endOfYear();

        return DailySalesResult::where('office_id', $this->officeId)
            ->whereBetween('target_date', [$startDate, $endDate])
            ->select(
                'order',
                DB::raw("DATE_FORMAT(target_date, '%Y%m') as result_month"),
                DB::raw('SUM(sales_target) as total_sales')
            )
            ->groupBy('order', 'result_month')
            ->get();
    }
}
