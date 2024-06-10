<?php
namespace App\Http\Controllers\Manage\Commons;

use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Models\Deal;
use App\Models\GoodCategory;
use App\Models\MonthlySalesTarget;
use App\Models\Office;
use App\Models\Payment;
use App\Models\PaymentGood;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait HeaderInfo
{
    function shareHeaderInfo()
    {
        $today = Carbon::today();
        $officeId = config('const.commons.office_id');
        $stats = new HeaderInfoStats;
        // 入庫予定
        $stats->loadDateCount = Deal::where('office_id', $officeId)
            ->whereDate('load_date', $today)
            ->whereNot('status', DealStatus::CANCEL->value)
            ->count();
        // 残り
        $stats->loadDateRemainingCount = Deal::where('office_id', $officeId)
            ->whereDate('load_date', $today)
            ->where('status', DealStatus::NOT_LOADED->value)
            ->count();
        // 出庫予定
        $stats->unloadDateCount = Deal::where('office_id', $officeId)
            ->whereDate('unload_date_plan', $today)
            ->whereIn('status', [DealStatus::LOADED->value, DealStatus::UNLOADED->value])
            ->count();
        // 残り
        $stats->unloadDateRemainingCount = Deal::where('office_id', $officeId)
            ->whereDate('unload_date_plan', $today)
            ->where('status', DealStatus::LOADED->value)
            ->count();

        $targetMonth = $today->format('Ym');

        $monthlySalesTargets = MonthlySalesTarget::where('office_id', $officeId)
            ->where('target_month', $targetMonth)
            ->whereBetween('order', [1, 5])
            ->orderBy('order')
            ->get();
        $goodCategoryIds = array_filter($monthlySalesTargets->pluck('good_category_id')->toArray());
        $monthlySalesTargetMap = getKeyMapCollection($monthlySalesTargets, 'order');

        $salesTotal = Payment::where('office_id', $officeId)
            ->whereDate('payment_date', $today)
            ->selectRaw('SUM(demand_price) AS demand_price')
            ->selectRaw('SUM(price) AS price')
            ->first();
            // ->sum('demand_price', 'price');

        $goodSalesTotalMap = getKeyMapCollection(
            PaymentGood::whereHas('payment', function($query) use($today, $officeId) {
                $query->where('office_id', $officeId)
                ->whereDate('payment_date', $today);
            })
            ->whereIn('good_category_id', $goodCategoryIds)
            ->groupBy('good_category_id')
            ->select('good_category_id')
            ->selectRaw('SUM(total_price) AS total_price')
            ->selectRaw('SUM(total_tax) AS total_tax')
            ->get(),
            // ->sum('total_price', 'total_tax'),
            'good_category_id');

        $stats->rows[MonthlySalesTarget::TOTAL_SALES_ORDER] = new HeaderInfoStatsRow(
            '月間売上目標',
            $salesTotal->demand_price,
            Arr::get($monthlySalesTargetMap, MonthlySalesTarget::TOTAL_SALES_ORDER)?->sales_target_per_day
        );
        $stats->rows[MonthlySalesTarget::PARKING_FEE] = new HeaderInfoStatsRow(
            '駐車料金',
            roundTax(TaxType::TEN_PERCENT->rate() * $salesTotal->price + $salesTotal->price),
            Arr::get( $monthlySalesTargetMap, MonthlySalesTarget::PARKING_FEE)?->sales_target_per_day
        );

        foreach (MonthlySalesTarget::GOOD_CATEGORY_ORDERS as $order) {
            if(!isset($monthlySalesTargetMap[$order])) {
                continue;
            }
            $monthlySalesTarget = $monthlySalesTargetMap[$order];
            $goodSalesTotal = Arr::get( $goodSalesTotalMap, $monthlySalesTarget->good_category_id);
            $totalAmount = 0;
            if($goodSalesTotal) {
                $totalAmount = $goodSalesTotal->total_price + $goodSalesTotal->total_tax;
            }
            $stats->rows[$order] = new HeaderInfoStatsRow(
                $monthlySalesTarget->goodCategory?->name,
                $totalAmount,
                Arr::get( $monthlySalesTargetMap, $order)?->sales_target_per_day
            );
        }

        view()->share('office', Office::find($officeId));
        view()->share('user', Auth::guard('web')->user());
        view()->share('stats', $stats);
    }
}
