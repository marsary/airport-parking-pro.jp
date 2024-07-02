<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\Controller;
use App\Http\Controllers\Manage\Forms\ManageReserveForm;
use App\Http\Requests\Manage\EntryDateRequest;
use App\Http\Requests\Manage\EntryInfoRequest;
use App\Models\Agency;
use App\Models\Airline;
use App\Models\ArrivalFlight;
use App\Models\Car;
use App\Models\CarCaution;
use App\Models\CarColor;
use App\Models\CarMaker;
use App\Models\Coupon;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\Member;
use App\Services\LabelTagManager;
use App\Services\Member\ReserveService;
use App\Services\PriceTable;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function entryDate()
    {
        $reserve = $this->getReserveForm();

        return view('manage.reserves.entry_date', [
            'reserve' => $reserve,
            'action' => route('manage.reserves.entry_date'),
            'method' => 'POST',
        ]);
    }

    public function postEntryDate(EntryDateRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());

        $table = PriceTable::getPriceTable($reserve->load_date, $reserve->unload_date_plan, $reserve->coupon_ids, $reserve->agency_id);
        $reserve->fill([
            'price' => $table->discountedSubTotal,
            'tax' => $table->tax,
            'num_days' => $table->numDays,
            'total_tax' => $table->tax,
            'total_price' => $table->discountedSubTotal,
        ]);
        session()->put('manage_reserve', $reserve);
        return redirect()->route('manage.reserves.entry_info');
    }

    public function entryInfo()
    {
        $reserve = $this->getReserveForm();
        $carMakers = CarMaker::select('name', 'id')->get();
        $cars = [];
        if(null != old('car_maker_id', $reserve->car_maker_id)) {
            $cars = Car::where('car_maker_id', old('car_maker_id', $reserve->car_maker_id))->select('name', 'id')->get();
        }
        $carColors = CarColor::select('name', 'id')->get();
        $goodCategories = GoodCategory::with('goods')->get();
        $goods = Good::all();
        $goodsMap = getKeyMapCollection($goods);
        $carCautions = CarCaution::where('office_id', $reserve->office_id)->get();
        $airlines = Airline::select('name', 'id')->get();


        return view('manage.reserves.entry_info', [
            'reserve' => $reserve,
            'carMakers' => $carMakers,
            'cars' => $cars,
            'carColors' => $carColors,
            'goodCategories' => $goodCategories,
            'goodsMap' => $goodsMap,
            'carCautions' => $carCautions,
            'airlines' => $airlines,
        ]);
    }


    public function postEntryInfo(EntryInfoRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->setMember($this->getMember($request));
        if($request->flight_no && $request->arrive_date) {
            $arrivalFlight = DB::table('arrival_flights')
                ->where('airline_id', $request->airline_id)
                ->where('flight_no', $request->flight_no)
                ->where('arrive_date', $request->arrive_date)
                ->first();
            $reserve->arr_flight_id = $arrivalFlight->id;
        }
        $reserve->fill($request->all());
        // 到着便の到着日と出庫日が異なる場合にチェック
        $reserve->arrival_flg = ($reserve->unload_date_plan == $reserve->arrive_date)? false : true;
        $reserve->visit_date_plan = $reserve->unload_date_plan;
        $reserve->setCarCautions();

        session()->put('manage_reserve', $reserve);
        return redirect()->route('manage.reserves.confirm');
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
        $agency = Agency::find($reserve->agency_id);

        return view('manage.reserves.confirm', [
            'reserve' => $reserve,
            'arrivalFlight' => $arrivalFlight,
            'carMaker' => $carMaker,
            'car' => $car,
            'carColor' => $carColor,
            'agency' => $agency,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reserve = $this->getReserveForm();
        $service = new ReserveService($reserve);
        try {
            DB::transaction(function () use($service){
                $service->store();
            });
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '予約登録に失敗しました。予約をやり直してください。');
        }
        //
        session()->forget('manage_reserve');

        if($request->has('to_register')) {
            return redirect(route('manage.registers.index', ['deal_id' => $service->deal->id]));
        }

        return redirect(route('manage.deals.index'));
    }


    private function getReserveForm():ManageReserveForm
    {
        $reserve = session()->get('manage_reserve');
        if(!$reserve) {
            $reserve = new ManageReserveForm();
        }
        return $reserve;
    }


    private function getMember(Request $request)
    {
        $kana = $request->input('kana');
        $tel = $request->input('tel');

        return Member::where('kana', $kana)->where('tel', $tel)->first();
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
