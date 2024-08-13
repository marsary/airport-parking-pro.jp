<?php

namespace App\Http\Controllers\Manage\Ledger;

use App\Enums\DealStatus;
use App\Http\Controllers\Manage\Controller;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoriesController extends Controller
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
        $query = Deal::query()->whereDate('load_date',$today);
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
        $loadDeals = $query->with(['member', 'arrivalFlight', 'memberCar'])
            ->orderBy('load_time', 'asc')
            ->get()
            ;
        // 本日出庫一覧
        $query = Deal::query()->where(function($query) use($today){
            $query->whereDate('unload_date_plan',$today)
                ->whereIn('status', [
                    DealStatus::LOADED->value,
                    DealStatus::PENDING->value,
                ]);
        });
        if($dispLoadedUnloaded) {
            $query->orWhere(function($query) use($today){
                $query->whereDate('unload_date',$today)
                    ->whereIn('status', [
                        DealStatus::UNLOADED->value,
                    ]);
            });
        }
        $unloadDeals = $query->with(['member', 'arrivalFlight', 'memberCar'])
            ->orderByRaw("COALESCE(unload_time, unload_time_plan) DESC")
            ->get()
            ;

        return view('manage.ledger.inventories', [
            'loadDeals' => $loadDeals,
            'unloadDeals' => $unloadDeals,
        ]);
    }
}
