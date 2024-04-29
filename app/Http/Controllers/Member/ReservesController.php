<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Member\Forms\ReserveForm;
use App\Http\Requests\Member\EntryCarRequest;
use App\Http\Requests\Member\EntryDateRequest;
use App\Http\Requests\Member\EntryInfoRequest;
use App\Http\Requests\Member\OptionSelectRequest;
use App\Models\ArrivalFlight;
use App\Models\Car;
use App\Models\CarColor;
use App\Models\CarMaker;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\MemberCar;
use App\Services\LabelTagManager;
use App\Services\Member\ReserveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function entryDate()
    {
        $reserve = $this->getReserveForm();

        return view('member.reserves.entry_date', [
            'reserve' => $reserve
        ]);
    }

    public function postEntryDate(EntryDateRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.entry_info');
    }

    public function entryInfo()
    {
        $reserve = $this->getReserveForm();

        return view('member.reserves.entry_info', [
            'reserve' => $reserve
        ]);
    }

    public function postEntryInfo(EntryInfoRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.entry_car');
    }

    public function entryCar()
    {
        $reserve = $this->getReserveForm();
        $carMakers = CarMaker::select('name', 'id')->get();
        $cars = [];
        if(null != old('car_maker_id', $reserve->car_maker_id)) {
            $cars = Car::where('car_maker_id', old('car_maker_id', $reserve->car_maker_id))->select('name', 'id')->get();
        }
        $carColors = CarColor::select('name', 'id')->get();

        return view('member.reserves.entry_car', [
            'reserve' => $reserve,
            'carMakers' => $carMakers,
            'cars' => $cars,
            'carColors' => $carColors,
        ]);
    }

    public function postEntryCar(EntryCarRequest $request)
    {
        $reserve = $this->getReserveForm();
        if($request->flight_no && $request->arrive_date) {
            $arrivalFlight = DB::table('arrival_flights')
                ->where('flight_no', $request->flight_no)
                ->where('arrive_date', $request->arrive_date)
                ->first();
            $reserve->arr_flight_id = $arrivalFlight->id;
        }

        $reserve->fill($request->all());
        // 到着便の到着日と出庫日が異なる場合にチェック
        $reserve->arrival_flg = ($reserve->unload_date_plan == $reserve->arrive_date)? false : true;
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.option_select');
    }

    public function optionSelect()
    {
        $reserve = $this->getReserveForm();

        $goodCategories = GoodCategory::with('goods')->get();
        $goods = Good::all();
        $goodsMap = getKeyMapCollection($goods);
        $coupons = Coupon::whereDate('start_date','<=', $reserve->load_date->toDateString())
            ->whereDate('end_date','>', $reserve->load_date->toDateString())
            ->get();
        $couponsMap = getKeyMapCollection($coupons);

        return view('member.reserves.option_select', [
            'reserve' => $reserve,
            'goodCategories' => $goodCategories,
            'goodsMap' => $goodsMap,
            'couponsMap' => $couponsMap,
        ]);
    }

    public function postOptionSelect(OptionSelectRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.confirm');
    }

    public function confirm()
    {
        $reserve = $this->getReserveForm();
        $reserve->handleGoodsAndTotals();

        LabelTagManager::attachTagDataToMember($reserve->member);
        $arrivalFlight = ArrivalFlight::with('airline','depAirport','arrAirport')->where('flight_no', $reserve->flight_no)
            ->where('arrive_date', $reserve->arrive_date)->first();
        $carMaker = CarMaker::where('id', $reserve->car_maker_id)->first();
        $car = Car::where('id', $reserve->car_id)->first();
        $carColor = CarColor::where('id', $reserve->car_color_id)->first();

        return view('member.reserves.confirm', [
            'reserve' => $reserve,
            'arrivalFlight' => $arrivalFlight,
            'carMaker' => $carMaker,
            'car' => $car,
            'carColor' => $carColor,
        ]);
    }


    private function getReserveForm():ReserveForm
    {
        $reserve = session()->get('reserve');
        if(!$reserve) {
            $reserve = new ReserveForm();
            $member = Auth::guard('members')->user();
            $reserve->setMember($member);
        }
        return $reserve;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $reserve = $this->getReserveForm();
        $service = new ReserveService($reserve);
        session()->forget('reserve');

        return redirect(route('reserves.complete', ['code' => (string) $service->deal->reserve_code]));
    }


    public function complete(Request $request)
    {
        return view('member.reserves.complete', [
            'reserveCode' => $request->query('code')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
