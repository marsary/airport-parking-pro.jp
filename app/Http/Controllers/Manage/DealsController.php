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

        if(empty(session()->getOldInput())) {
            session()->flash('_old_input', $request->all());
        }

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
            ->paginate($request->input('limit') ?? 25)
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
            // 予約情報
            ->when($request->input('reserve_code'), function($query, $search){
                $query->where('reserve_code', $search);
            })
            ->when($request->input('receipt_code'), function($query, $search){
                $query->where('receipt_code', $search);
            })
            ->when($request->input('reserve_date'), function($query, $search){
                $datetime = Carbon::parse($search);
                $query->whereDate('reserve_date',$datetime);
            })
            ->when($request->input('agency_id'), function($query, $search){
                $query->where('agency_id', $search);
            })
            ->when($request->input('load_date_start'), function($query, $search){
                $datetime = Carbon::parse($search)->toDateString();
                $query->where('load_date','>=', $datetime);
            })
            ->when($request->input('load_date_end'), function($query, $search){
                $datetime = Carbon::parse($search)->toDateString();
                $query->where('load_date','<=', $datetime);
            })
            ->when($request->input('load_time_start'), function($query, $search){
                $query->whereTime('load_time','>=', $search);
            })
            ->when($request->input('load_time_end'), function($query, $search){
                $query->whereTime('load_time','<=', $search);
            })
            ->when($request->input('unload_date_plan_start'), function($query, $search){
                $datetime = Carbon::parse($search)->toDateString();
                $query->where('unload_date_plan','>=', $datetime);
            })
            ->when($request->input('unload_date_plan_end'), function($query, $search){
                $datetime = Carbon::parse($search)->toDateString();
                $query->where('unload_date_plan','<=', $datetime);
            })
            ->when($request->input('unload_date_start'), function($query, $search){
                $datetime = Carbon::parse($search)->toDateString();
                $query->where('unload_date','>=', $datetime);
            })
            ->when($request->input('unload_date_end'), function($query, $search){
                $datetime = Carbon::parse($search)->toDateString();
                $query->where('unload_date','<=', $datetime);
            })
            ->when($request->input('num_days_start'), function($query, $search){
                $query->where('num_days','>=', $search);
            })
            ->when($request->input('num_days_end'), function($query, $search){
                $query->where('num_days','<=', $search);
            })
            // 顧客情報
            ->when($request->input('member_code'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('member_code', $search);
                });
            })
            ->when($request->input('name'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('name', $search);
                });
            })
            ->when($request->input('kana'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('kana', $search);
                });
            })
            ->when($request->input('used_num'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('used_num', $search);
                });
            })
            ->when($request->input('label_tag'), function($query, $search){
                foreach ($search as $labelId => $tagId) {
                    $query->whereHas('member.tagMembers', function (Builder $query) use($labelId, $tagId){
                        $query->where('label_id', $labelId)
                            ->where('tag_id', $tagId);
                    });
                }

            })
            ->when($request->input('zip'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('zip', $search);
                });
            })
            ->when($request->input('tel'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('tel', $search);
                });
            })
            ->when($request->input('email'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('email', $search);
                });
            })
            ->when($request->input('line_id'), function($query, $search){
                $query->whereHas('member', function (Builder $query) use($search){
                    $query->where('line_id', $search);
                });
            })
            ->when($request->input('receipt_address'), function($query, $search){
                $query->where('receipt_address', $search);
            })
            // 到着予定
            ->when($request->input('arrive_date_start'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $datetime = Carbon::parse($search)->toDateString();
                    $query->where('arrive_date','>=', $datetime);
                });
            })
            ->when($request->input('arrive_date_end'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $datetime = Carbon::parse($search)->toDateString();
                    $query->where('arrive_date','<=', $datetime);
                });
            })
            ->when($request->input('arrive_time_start'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->whereTime('arrive_time','>=', $search);
                });
            })
            ->when($request->input('arrive_time_end'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->whereTime('arrive_time','<=', $search);
                });
            })
            ->when($request->input('arrival_flight_name'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->where('flight_no', $search);
                });
            })
            ->when($request->input('airline_id'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->where('airline_id', $search);
                });
            })
            ->when($request->input('dep_airport_id'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->where('dep_airport_id', $search);
                });
            })
            ->when($request->input('arr_airport_id'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->where('arr_airport_id', $search);
                });
            })
            ->when($request->input('terminal_id'), function($query, $search){
                $query->whereHas('arrivalFlight', function (Builder $query) use($search){
                    $query->where('terminal_id', $search);
                });
            })
            ->when($request->input('arrival_flg'), function($query, $search){
                $query->where('arrival_flg', $search);
            })
            // 車両情報
            ->when($request->input('car_maker_id'), function($query, $search){
                $query->whereHas('memberCar.car', function (Builder $query) use($search){
                    $query->where('car_maker_id', $search);
                });
            })
            ->when($request->input('car_id'), function($query, $search){
                $query->whereHas('memberCar', function (Builder $query) use($search){
                    $query->where('car_id', $search);
                });
            })
            ->when($request->input('car_color_id'), function($query, $search){
                $query->whereHas('memberCar', function (Builder $query) use($search){
                    $query->where('car_color_id', $search);
                });
            })
            ->when($request->input('number'), function($query, $search){
                $query->whereHas('memberCar', function (Builder $query) use($search){
                    $query->where('number', $search);
                });
            })
            ->when($request->input('size_type'), function($query, $search){
                $query->whereHas('memberCar.car', function (Builder $query) use($search){
                    $query->where('size_type', $search);
                });
            })
            ->when($request->input('num_members'), function($query, $search){
                $query->where('num_members', $search);
            })
            // 車両取扱
            ->when($request->input('car_caution_id'), function($query, $search){
                $query->whereHas('carCautionMemberCars', function (Builder $query) use($search){
                    $query->where('car_caution_id', $search);
                });
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
