<?php

namespace App\Services\Ledger\Repositories;

use App\Enums\DealStatus;
use App\Enums\PaymentMethodType;
use App\Enums\TransactionType;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PaymentGood;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PaymentSummaryRepository implements PaymentSummaryRepositoryInterface
{
    private function getOfficeId(): int
    {
        return config('const.commons.office_id');
    }

    public function getLoadUnloadCounts(Request $request)
    {
        $officeId = $this->getOfficeId();

        $loadQuery = Deal::where('deals.office_id', $officeId)
            ->when($request->input('entry_date_start'), function($query, $search){
                $datetime = Carbon::parse($search)->startOfDay()->toDateTimeString();
                $query->where('load_date','>=', $datetime);
            })
            ->when($request->input('entry_date_fin'), function($query, $search){
                $datetime = Carbon::parse($search)->endOfDay()->toDateTimeString();
                $query->where('load_date','<=', $datetime);
            })
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('transaction_type', TransactionType::PURCHASE_ONLY->value)
            ->select('agency_id', DB::raw('count(*) as count'))
            ->groupBy('agency_id');

        $unloadQuery = Deal::where('deals.office_id', $officeId)
            ->when($request->input('entry_date_start'), function($query, $search){
                $datetime = Carbon::parse($search)->startOfDay()->toDateTimeString();
                $query->where('unload_date','>=', $datetime);
            })
            ->when($request->input('entry_date_fin'), function($query, $search){
                $datetime = Carbon::parse($search)->endOfDay()->toDateTimeString();
                $query->where('unload_date','<=', $datetime);
            })
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('transaction_type', TransactionType::PURCHASE_ONLY->value)
            ->select('agency_id', DB::raw('count(*) as count'))
            ->groupBy('agency_id');

        $pendingQuery = Deal::where('deals.office_id', $officeId)
            ->when($request->input('entry_date_fin'), function($query, $search){
                $datetime = Carbon::parse($search)->endOfDay()->toDateTimeString();
                // 入庫日 <= 基準日 AND
                // 出庫予定日 <= 基準日 AND
                // (出庫日がnull でない AND 出庫予定日 < 出庫日 AND 基準日 < 出庫日) OR
                // (出庫日がnull)
                $query->where('load_date','<=', $datetime)
                    ->where('unload_date_plan', '<=', $datetime)
                    ->where(function ($query) use ($datetime) {
                        $query->where(function ($q) use ($datetime) {
                            $q->whereNotNull('unload_date')
                            ->whereRaw('? < unload_date', [$datetime]);
                        })
                        ->orWhere(function ($q) {
                            $q->whereNull('unload_date');
                        });
                    });
            })
            ->whereNot('transaction_type', TransactionType::PURCHASE_ONLY->value)
            ->whereNot('status', DealStatus::CANCEL->value)
            ->select('agency_id', DB::raw('count(*) as count'))
            ->groupBy('agency_id');


        $loadedQuery = Deal::where('deals.office_id', $officeId)
            ->when($request->input('entry_date_fin'), function($query, $search){
                $datetime = Carbon::parse($search)->endOfDay()->toDateTimeString();
                // 入庫日 <= 基準日 AND
                // (出庫日がnull でない AND 出庫日 > 基準日) OR
                // (出庫日がnull AND 出庫予定日 > 基準日)
                $query->where('load_date', '<=', $datetime)
                    ->where(function ($query) use ($datetime) {
                        $query->where(function ($q) use ($datetime) {
                            $q->whereNotNull('unload_date')
                            ->where('unload_date', '>', $datetime);
                        })
                        ->orWhere(function ($q) use ($datetime) {
                            $q->whereNull('unload_date')
                            ->where('unload_date_plan', '>', $datetime);
                        });
                    });
            })
            ->whereNot('transaction_type', TransactionType::PURCHASE_ONLY->value)
            ->whereNotIn('status', [DealStatus::CANCEL->value, DealStatus::NOT_LOADED->value])
            ->select('agency_id', DB::raw('count(*) as count'))
            ->groupBy('agency_id');

        return [
            'load' => $loadQuery->get(),
            'unload' => $unloadQuery->get(),
            'pending' => $pendingQuery->get(),
            'loaded' => $loadedQuery->get(),
        ];
    }

    public function getPaymentMethodTableData(Request $request)
    {
        $startDate = Carbon::parse($request->input('entry_date_start'))->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->input('entry_date_fin'))->endOfDay()->toDateTimeString();
        $officeId = $this->getOfficeId();

        return PaymentDetail::whereHas('payment', function (Builder $query) use ($officeId, $startDate, $endDate) {
                $query->where('payments.office_id', $officeId)
                    ->where('payment_date', '>=', $startDate)
                    ->where('payment_date', '<=', $endDate);
            })
            ->join('payment_methods', 'payment_details.payment_method_id', '=', 'payment_methods.id')
            ->select('payment_methods.type', DB::raw('COUNT(*) as count'), DB::raw('SUM(payment_details.total_price) as price'))
            ->groupBy('payment_details.payment_method_id', 'payment_methods.type')
            ->orderBy('payment_details.payment_method_id', 'asc')
            ->get();
    }

    public function getPaymentGoodsData(Request $request)
    {
        $startDate = Carbon::parse($request->input('entry_date_start'))->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->input('entry_date_fin'))->endOfDay()->toDateTimeString();
        $officeId = $this->getOfficeId();

        $parkingData = Payment::where('payments.office_id', $officeId)
            ->where('payment_date', '>=', $startDate)
            ->where('payment_date', '<=', $endDate)
            ->join('deals', 'deals.id', '=', 'payments.deal_id')
            ->select('deals.agency_id', DB::raw('count(*) as count'), DB::raw('SUM(payments.price) as price'))
            ->groupBy('deals.agency_id')
            ->get();

        $goodsData = PaymentGood::whereHas('payment', function (Builder $query) use ($startDate, $endDate, $officeId) {
                $query->where('payment_date', '>=', $startDate)
                    ->where('payment_date', '<=', $endDate)
                    ->where('office_id', $officeId);
            })
            ->join('payments', 'payments.id', '=', 'payment_goods.payment_id')
            ->join('deals', 'deals.id', '=', 'payments.deal_id')
            ->join('good_categories', 'payment_goods.good_category_id', '=', 'good_categories.id')
            ->select('deals.agency_id', 'good_categories.name as category_name', DB::raw('count(*) as count'), DB::raw('SUM(payment_goods.total_price+payment_goods.total_tax) as price'))
            ->groupBy('deals.agency_id', 'good_categories.name')
            ->orderBy('good_categories.id', 'asc')
            ->get();

        $cashData = Payment::where('payments.office_id', $officeId)
            ->where('payment_date', '>=', $startDate)
            ->where('payment_date', '<=', $endDate)
            ->join('deals', 'deals.id', '=', 'payments.deal_id')
            ->select('deals.agency_id', DB::raw('count(*) as count'), DB::raw('SUM(payments.cash_change) as price'))
            ->groupBy('deals.agency_id')
            ->get();

        return [
            'parkingData' => $parkingData,
            'goodsData' => $goodsData,
            'cashData' => $cashData,
        ];
    }

    public function getCreditTableOfficeData(Request $request)
    {
        $startDate = Carbon::parse($request->input('entry_date_start'))->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->input('entry_date_fin'))->endOfDay()->toDateTimeString();
        $officeId = $this->getOfficeId();

        return PaymentDetail::whereHas('payment', function (Builder $query) use($officeId, $startDate, $endDate){
                $query->where('payments.office_id', $officeId)
                    ->where('payment_date','>=', $startDate)
                    ->where('payment_date','<=', $endDate);
            })
            ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
            ->join('deals', 'deals.id', '=', 'payments.deal_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'payment_details.payment_method_id')
            ->where('payment_methods.type', PaymentMethodType::CREDIT->value)
            ->select('deals.agency_id', DB::raw('count(*) as count'), DB::raw('SUM(payment_details.total_price) as price'))
            ->groupBy('deals.agency_id')
            ->orderBy('deals.agency_id', 'asc')
            ->get();
    }
}
