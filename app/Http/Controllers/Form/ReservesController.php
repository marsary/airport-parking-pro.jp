<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Form\Forms\ReserveForm;
use App\Http\Requests\Form\OptionSelectRequest;
use App\Http\Requests\Form\EntryCarRequest;
use App\Http\Requests\Form\EntryDateRequest;
use App\Http\Requests\Form\EntryInfoRequest;
use App\Mail\DealCreatedThankyouMail;
use App\Mail\DealCreatedWebAdminMail;
use App\Models\Agency;
use App\Models\Airline;
use App\Models\ArrivalFlight;
use App\Models\Car;
use App\Models\CarColor;
use App\Models\CarMaker;
use App\Models\Deal;
use App\Models\Member;
use App\Services\LabelTagManager;
use App\Services\Form\ReserveService;
use App\Services\PriceTable;
use App\Services\Settings\SeasonPriceSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class ReservesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function entryDate(Request $request)
    {
        $reserve = $this->getReserveForm();

        return view('form.reserves.entry_date', [
            'reserve' => $reserve,
        ]);
    }

    public function postEntryDate(EntryDateRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());

        $table = PriceTable::getPriceTable($reserve->load_date, $reserve->unload_date_plan, [], $reserve->agency_id);
        $seasonPriceData = SeasonPriceSettingService::getSeasonPrice($reserve->load_date, $reserve->unload_date_plan);
        $reserve->fill([
            'price' => $table->subTotal,
            'tax' => $table->tax,
            'num_days' => $table->numDays,
            'season_price' => $seasonPriceData['season_price'],
            'season_price_tax' => $seasonPriceData['season_price_tax'],
        ]);
        session()->put('reserve', $reserve);

        return redirect()->route('form.reserves.entry_info');
    }

    public function entryInfo(Request $request)
    {
        $reserve = $this->getReserveForm();

        return view('form.reserves.entry_info', [
            'reserve' => $reserve
        ]);
    }

    public function postEntryInfo(EntryInfoRequest $request)
    {
        $reserve = $this->getReserveForm();

        $memberExists = Member::where('email', $request->email)->exists();

        if($memberExists && !$reserve->member) {
            $member = Member::where('email', $request->email)->first();
            $reserve->setMember($member);
        }
        $reserve->fill($request->all());

        if($reserve->registerMember) {
            $reserve->fillMember();
        }

        session()->put('reserve', $reserve);
        return redirect()->route('form.reserves.entry_car');
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
        $airlines = Airline::select('name', 'id')->get();

        return view('form.reserves.entry_car', [
            'reserve' => $reserve,
            'carMakers' => $carMakers,
            'cars' => $cars,
            'carColors' => $carColors,
            'airlines' => $airlines,
        ]);
    }

    public function postEntryCar(EntryCarRequest $request)
    {
        $reserve = $this->getReserveForm();
        if($request->flight_no && $request->arrive_date) {
            $arrivalFlight = DB::table('arrival_flights')
                ->where('airline_id', $request->airline_id)
                ->where('flight_no', $request->flight_no)
                ->where('arrive_date', $request->arrive_date)
                ->first();
            $reserve->arr_flight_id = $arrivalFlight?->id;
        }

        $reserve->fill($request->all());
        // 到着便の到着日と出庫日が異なる場合にチェック
        $reserve->arrival_flg = ($reserve->unload_date_plan == $reserve->arrive_date)? false : true;

        session()->put('reserve', $reserve);
        return redirect()->route('form.reserves.option_select');
    }

    public function optionSelect()
    {
        $reserve = $this->getReserveForm();

        return view('form.reserves.option_select', [
            'reserve' => $reserve,
        ]);
    }

    public function postOptionSelect(OptionSelectRequest $request)
    {
// error_log("postOptionSelect\n",3,"../storage/logs/test.log");
        // error_log(json_encode($request)."\n",3,"../storage/logs/test.log");
        $reserve = $this->getReserveForm();
// error_log("reserve\n",3,"../storage/logs/test.log");
//          error_log(json_encode($reserve)."\n",3,"../storage/logs/test.log");

        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('form.reserves.confirm');
    }

    public function confirm()
    {
        $reserve = $this->getReserveForm();
        $reserve->handleGoodsAndTotals();

        // LabelTagManager::attachTagDataToMember($reserve->member);
        $arrivalFlight = null;
        if($reserve->flight_no && $reserve->arrive_date) {
            $arrivalFlight = ArrivalFlight::with('airline','depAirport','arrAirport')
                ->where('airline_id', $reserve->airline_id)
                ->where('flight_no', $reserve->flight_no)
                ->where('arrive_date', $reserve->arrive_date)->first();
        }
        $carMaker = CarMaker::where('id', $reserve->car_maker_id)->first();
        $car = Car::where('id', $reserve->car_id)->first();
        $carColor = CarColor::where('id', $reserve->car_color_id)->first();
        $agency = Agency::find($reserve->agency_id);

        return view('form.reserves.confirm', [
            'reserve' => $reserve,
            'arrivalFlight' => $arrivalFlight,
            'airline' => $reserve->airline_id ? Airline::find($reserve->airline_id): null,
            'carMaker' => $carMaker,
            'car' => $car,
            'carColor' => $carColor,
            'agency' => $agency,
        ]);
    }


    private function getReserveForm():ReserveForm
    {
        $reserve = session()->get('reserve');
            error_log("reserve\n",3,"../storage/logs/test.log");
error_log(json_decode($reserve)."\n",3,"../logs/test.log");
        if(!$reserve) {
            error_log("if\n",3,"../storage/logs/test.log");
            error_log($this->reserve->member->id."\n",3,"../storage/logs/test.log");
            $reserve = new ReserveForm();
            $member = Auth::guard('members')->user();
            $reserve->setMember($member);
            // 会員情報は登録・更新する
            $reserve->registerMember = true;
        } elseif (!$reserve->member && Auth::guard('members')->check()) {
            error_log("else\n",3,"../storage/logs/test.log");
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
        try {
            DB::transaction(function () use($service){
                $service->store();
                // 現状SOCにデータを送る場合は、パスワード設定は行わない
                // 事業所のメールアドレスに「管理者宛メール」を、取引のメールアドレスに「サンキューメール」を送信
                Mail::to(myOffice()->email)->send(new DealCreatedWebAdminMail($service->deal));

                Mail::to($service->deal->email)->send(new DealCreatedThankyouMail($service->deal));

            });

        } catch (TransportException $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '予約完了メールの送信に失敗しました。正しいメールを入力してください。');
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '予約登録に失敗しました。予約をやり直してください。');
        }
        session()->forget('reserve');

        return redirect(route('form.reserves.complete', ['code' => (string) $service->deal->reserve_code]));
    }


    public function complete(Request $request)
    {
        Auth::guard('members')->logout();
        return view('form.reserves.complete', [
            'reserveCode' => $request->query('code'),
            'deal' => Deal::where('reserve_code', $request->query('code'))->first(),
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
