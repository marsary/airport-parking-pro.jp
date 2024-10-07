<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\BunchIssuesRequest;
use App\Http\Requests\Manage\UnloadAllRequest;
use App\Models\Deal;
use App\Models\GoodCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function inventories(Request $request)
    {
        // dd($request->all());
        $dispLoadedUnloaded = $request->has('disp_loaded_unloaded') ? true:false;

        $today = Carbon::today();
        // 本日入庫一覧
        $query = Deal::query()->whereDate('load_date',$today)->withCount('payment');
        if($dispLoadedUnloaded) {
            $query->whereIn('status', [
                DealStatus::NOT_LOADED->value,
                DealStatus::LOADED->value,
            ]);
        } else {
            $query->whereIn('status', [
                DealStatus::NOT_LOADED->value,
            ]);
        }
        $loadDeals = $query->with(['member.memberType', 'arrivalFlight.depAirport', 'memberCar.car', 'memberCar.carColor'])
            ->orderBy('load_time', 'asc')
            ->get()
            ;

        // 本日出庫一覧
        $query = Deal::query()->withCount('payment')->where(function($query) use($today){
            $query->whereDate('unload_date_plan',$today)
                ->whereIn('status', [
                    DealStatus::LOADED->value,
                    DealStatus::PENDING->value,
                ]);
        });
        if($dispLoadedUnloaded) {
            $query->orWhere(function($query) use($today){
                $query->whereDate('unload_date_plan',$today)
                    ->whereIn('status', [
                        DealStatus::UNLOADED->value,
                    ]);
            });
        }
        $unloadDeals = $query->with(['member', 'arrivalFlight.depAirport', 'arrivalFlight.airportTerminal', 'memberCar.car', 'memberCar.carColor', 'dealGoods.good', 'carCautionMemberCars.carCaution','office'])
            ->orderByRaw("COALESCE(unload_time, unload_time_plan) ASC")
            ->get()
            ;


        if($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'loadDeals' => $loadDeals,
                'unloadDeals' => $unloadDeals,
                'senshaCategoryId' => GoodCategory::where('name', '洗車')->first()?->id
            ]);
        } else {
            return view('manage.ledger.inventories', [
                'loadDeals' => $loadDeals,
                'unloadDeals' => $unloadDeals,
                'senshaCategoryId' => GoodCategory::where('name', '洗車')->first()?->id
            ]);
        }
    }

    public function bunch_issues(BunchIssuesRequest $request)
    {
        // dd($request->all());

        $today = Carbon::today();

        // 出庫一覧データ
        $query = Deal::query()->withCount('payment')
        ->whereIn('status', [
            DealStatus::LOADED->value,
            // DealStatus::UNLOADED->value,
        ])
        ->where(function ($q) use ($today) {
            $q->where(function ($subQ) use ($today) {
                $subQ->whereNotNull('unload_date')
                ->where('unload_date', '<=', $today);
            })
            ->orWhere(function ($subQ) use ($today) {
                $subQ->whereNull('unload_date')
                    ->whereNotNull('unload_date_plan')
                    ->where('unload_date_plan', '<=', $today);
            });
        });

        if ($request->filled('start_date')) {
            $start_date = Carbon::parse($request->start_date)->toDateString();
            $query->where(function ($q) use ($start_date) {
                $q->where('unload_date', '>=', $start_date)
                  ->orWhere(function ($subQ) use ($start_date) {
                      $subQ->whereNull('unload_date')
                        ->whereNotNull('unload_date_plan')
                        ->where('unload_date_plan', '>=', $start_date);
                  });
            });
        }

        if ($request->filled('end_date')) {
            $end_date = Carbon::parse($request->end_date)->toDateString();
            $query->where(function ($q) use ($end_date) {
                $q->where('unload_date', '<=', $end_date)
                  ->orWhere(function ($subQ) use ($end_date) {
                      $subQ->whereNull('unload_date')
                        ->whereNotNull('unload_date_plan')
                        ->where('unload_date_plan', '<=', $end_date);
                  });
            });
        }

        $deals = $query->with(['member', 'arrivalFlight.depAirport', 'arrivalFlight.airportTerminal', 'memberCar.car', 'memberCar.carColor', 'dealGoods.good', 'carCautionMemberCars.carCaution','office'])
            ->orderByRaw("COALESCE(unload_date, unload_date_plan) ASC")
            ->paginate($request->input('limit') ?? 25)
            ->withQueryString()
            ;


        return view('manage.ledger.bunch_issues', [
            'deals' => $deals,
            'senshaCategoryId' => GoodCategory::where('name', '洗車')->first()?->id
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function unloadAll(UnloadAllRequest $request)
    {
        $now = Carbon::now();
        try {
            Deal::whereIn('id', $request->deal_id)->update([
                'status' => DealStatus::UNLOADED->value,
                'unload_date' => $now->toDateString(),
                'unload_time' => $now->toTimeString(),
            ]);

        } catch (\Throwable $th) {
            return back()->with('failure', 'ステータスを出庫済みへ変更する際にエラーが発生しました。');
        }

        return redirect()->route('manage.ledger.bunch_issues');
    }

}
