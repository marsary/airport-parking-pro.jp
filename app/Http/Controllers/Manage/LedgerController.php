<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Http\Controllers\Manage\Controller;
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

    public function bunch_issues(Request $request)
    {
        return view('manage.ledger.bunch_issues');
    }

}
