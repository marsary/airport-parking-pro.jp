<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Http\Controllers\Manage\Controller;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Services\Deal\DealGoodsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goodCategories = GoodCategory::with('goods')->get();
        $goods = Good::all();
        // $deals = Deal::whereNot('status', DealStatus::CANCEL->value)->get();
        $today = Carbon::today();
        $coupons = Coupon::whereDate('start_date','<=', $today->toDateString())
            ->whereDate('end_date','>', $today->toDateString())
            ->where('office_id', config('const.commons.office_id'))
            ->get();
        $goodsMap = getKeyMapCollection($goods);
        return view('manage.registers.index', [
            'goodCategories' => $goodCategories,
            'goodsMap' => $goodsMap,
            // 'deals' => $deals,
            'coupons' => $coupons,
        ]);
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
        $deal = Deal::findOrFail($id);
        $service = new DealGoodsService($deal);


        return response()->json([
            'success' => true,
            'data' => [
                'deal' => $deal,
                'dealGoods' => getKeyMapCollection($deal->dealGoods, 'good_id'),
                'totalPrices' => $service->sumTotals()
            ],
         ]);
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
