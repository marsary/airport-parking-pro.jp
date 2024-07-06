<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Exports\DealSearchExport;
use App\Http\Controllers\Manage\Controller;
use App\Http\Controllers\Manage\Forms\DealEditForm;
use App\Http\Requests\Manage\DealSearchRequest;
use App\Http\Requests\Manage\DealUpdateGoodsRequest;
use App\Http\Requests\Manage\DealUpdateMemoRequest;
use App\Http\Requests\Manage\EntryDateRequest;
use App\Http\Requests\Manage\UpdateDealRequest;
use App\Models\Agency;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Car;
use App\Models\CarCaution;
use App\Models\CarColor;
use App\Models\CarMaker;
use App\Models\Deal;
use App\Models\DealGood;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Services\Deal\DealService;
use App\Services\Deal\ExtraPaymentManager;
use App\Services\LabelTagManager;
use App\Services\PriceTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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


    public function searchExport(Request $request)
    {
        $dealIds = explode(',', $request->input('deal_ids'));

        /** @var DealSearchExport $export */
        $export = new DealSearchExport($dealIds);

        $fileName = Carbon::today()->format('Ymd') . '_search_result.xlsx';
        return $export->download($fileName);

    }



    public function entryDate($id)
    {
        $reserve = $this->getEditForm($id);

        return view('manage.reserves.entry_date', [
            'reserve' => $reserve,
            'action' => route('manage.deals.entry_date', [$id]),
            'method' => 'PUT',
        ]);
    }

    public function putEntryDate(EntryDateRequest $request, $id)
    {
        $reserve = $this->getEditForm($id);
        $reserve->fill($request->all());

        $table = PriceTable::getPriceTable($reserve->load_date, $reserve->unload_date_plan, $reserve->coupon_ids, $reserve->agency_id);
        $reserve->fill([
            'price' => $table->subTotal,
            'tax' => $table->tax,
            'num_days' => $table->numDays,
            'total_tax' => $table->tax,
            'total_price' => $table->subTotal,
        ]);
        session()->put('manage_edit_deal', $reserve);
        return redirect()->route('manage.deals.edit', [$id]);
    }


    public function edit($id)
    {
        $reserve = $this->getEditForm($id);
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


        return view('manage.deals.edit', [
            'reserve' => $reserve,
            'carMakers' => $carMakers,
            'cars' => $cars,
            'carColors' => $carColors,
            'goodCategories' => $goodCategories,
            'goodsMap' => $goodsMap,
            'carCautions' => $carCautions,
        ]);
    }


    public function update(UpdateDealRequest $request, $id)
    {
        if($request->has('cancel_btn')) {
            session()->forget('manage_edit_deal');
            return redirect()->route('manage.deals.show', [$id]);
        }

        $reserve = $this->getEditForm($id);
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
        $reserve->visit_date_plan = $reserve->unload_date_plan;
        // $reserve->setCarCautions();
        $reserve->handleGoodsAndTotals();

        $service = new DealService($reserve);
        try {
            DB::transaction(function () use($service){
                $service->update();
            });
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '予約更新に失敗しました。更新処理をやり直してください。');
        }
        //
        session()->forget('manage_edit_deal');

        return redirect()->route('manage.deals.show', [$id]);
    }


    private function getEditForm($id):DealEditForm
    {
        $reserve = session()->get('manage_edit_deal');
        if(!$reserve) {
            $reserve = new DealEditForm(Deal::findOrFail($id));
        }
        return $reserve;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deal = Deal::findOrFail($id);
        LabelTagManager::attachTagDataToMember($deal->member);
        $extraPayment = new ExtraPaymentManager($deal);
        $extraPayment->calcPayment();

        return view('manage.deals.detail', [
            'deal' => $deal,
            'arrivalFlight' => $deal->arrivalFlight,
            'extraPayment' => $extraPayment,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateMemo(DealUpdateMemoRequest $request, string $id)
    {
        $deal = Deal::findOrFail($id);

        if($request->has('save_member_memo_btn')) {
            $member = $deal->member;
            $member->memo = $request->member_memo;
            $member->save();
        } elseif($request->has('save_reserve_memo_btn')) {
            $deal->reserve_memo = $request->reserve_memo;
            $deal->save();
        } elseif($request->has('save_reception_memo_btn')) {
            $deal->reception_memo = $request->reception_memo;
            $deal->save();
        }

        return redirect(route('manage.deals.show', [$deal->id]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function unload(string $id)
    {
        $deal = Deal::findOrFail($id);
        $now = Carbon::now();
        $deal->unload_date = $now->toDateString();
        $deal->unload_time = $now->toTimeString();
        $deal->status = DealStatus::UNLOADED->value;
        $deal->save();

        return redirect(route('manage.deals.show', [$deal->id]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateGoods(DealUpdateGoodsRequest $request, string $id)
    {
        $deal = Deal::findOrFail($id);
        try {
            DB::transaction(function () use($request, $deal) {
                $deal->fill([
                    // 'dsc_rate' => $this->reserve->dsc_rate,
                    'total_price' => $request->total_price,
                    'total_tax' => $request->total_tax,
                ])->save();
                $deal->dealGoods()->delete();
                foreach ($request->dealGoods as $newDealGood) {
                    // dealGoods[goodId] = {
                    //     good_id,
                    //     num,
                    //     total_price,
                    //     total_tax
                    //   }
                    $newDealGood['deal_id'] = $deal->id;
                    $newDealGood['sales_date'] = Carbon::today();
                    DealGood::create($newDealGood);
                }
                $deal->load('dealGoods');

            });
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => '取引商品の更新に失敗しました。',
             ]);
        }


        return response()->json([
            'success' => true,
            'data' => [
                'deal' => $deal
            ],
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
