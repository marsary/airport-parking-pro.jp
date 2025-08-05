<?php

namespace App\Services\Ledger\Repositories;

use App\Enums\DealStatus;
use App\Enums\TransactionType;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    }

    public function getPaymentGoodsData(Request $request)
    {
    }

    public function getCreditTableOfficeData(Request $request)
    {
    }
}
