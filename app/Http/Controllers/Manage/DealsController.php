<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Exports\DealSearchExport;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\DealSearchRequest;
use App\Models\Agency;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Car;
use App\Models\CarCaution;
use App\Models\CarColor;
use App\Models\CarMaker;
use App\Models\Deal;
use App\Services\LabelTagManager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $agencies = Agency::select('name', 'id')->get();
        $carMakers = CarMaker::select('name', 'id')->get();
        $cars = Car::select('name', 'id')->get();
        $carColors = CarColor::select('name', 'id')->get();
        $airports = Airport::select('name', 'id')->get();
        $airlines = Airline::select('name', 'id')->get();
        $carCautions = CarCaution::where('office_id', config('const.commons.office_id'))
            ->select('name', 'id')->get();
        $labels = LabelTagManager::getLabelTags(config('const.commons.office_id'));

        return view('manage.deals.search', [
            'agencies' => $agencies,
            'carMakers' => $carMakers,
            'cars' => $cars,
            'carColors' => $carColors,
            'airports' => $airports,
            'airlines' => $airlines,
            'carCautions' => $carCautions,
            'labels' => $labels,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(DealSearchRequest $request)
    {
        // dd($request->all());
        $query = $this->getDealQueryFromRequest($request);
        $deals = $query->with(['member', 'agency'])
            ->orderBy('reserve_date', 'asc')
            ->paginate()
            ->withQueryString()
            ;

        return view('manage.deals.list', [
            'deals' => $deals,
        ]);
    }

    private function getDealQueryFromRequest(Request $request):Builder
    {
        $today = Carbon::today();

        $query = Deal::query();
        // 予約ステータス
        $query->when($request->input('reserved'), function($query, $search) {
                $query->whereNot('status', DealStatus::CANCEL->value);
            })
            ->when($request->input('load_today'), function($query, $search)use($today){
                $query->whereDate('load_date',$today);
            })
            ->when($request->input('loaded'), function($query, $search){
                $query->where('status', DealStatus::LOADED->value);
            })
            ->when($request->input('unload_plan_today'), function($query, $search)use($today){
                $query->whereDate('unload_date_plan',$today);
            })
            ->when($request->input('pending'), function($query, $search){
                $query->where('status', DealStatus::PENDING->value);
            })
            ->when($request->input('unloaded'), function($query, $search){
                $query->where('status', DealStatus::UNLOADED->value);
            })
            ;
            // $query->dd();
        return $query;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('manage.deals.detail');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('manage.deals.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
