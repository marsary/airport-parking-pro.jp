<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\RegisterStoreRequest;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\PaymentDetail;
use App\Models\PaymentMethod;
use App\Services\Deal\DealGoodsService;
use App\Services\Deal\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
            'paymentMethodCategoryMap' => PaymentMethod::getIdNameMapGroupedByCategory(),
            'dealId' => $request->input('deal_id'),
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
    public function store(RegisterStoreRequest $request)
    {
        try {
            DB::transaction(function () use($request) {
                $service = new PaymentService($request->input('deal_id'), $request->all());
                $service->save();
            });
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '決済処理に失敗しました。決済手続きをやり直してください。');
        }
        return redirect(route('manage.deals.show', [$request->deal_id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deal = Deal::findOrFail($id);
        $service = new DealGoodsService($deal);
        $categoryPaymentDetailMap = [];
        if($deal->payment?->paymentDetails) {
            foreach ($deal->payment->paymentDetails as $paymentDetail) {
                if(!isset($categoryDetails[$paymentDetail->paymentMethodCategory()]) && $paymentDetail->paymentMethod->multiple) {
                    $categoryDetails[$paymentDetail->paymentMethodCategory()] = [];
                }
                if($paymentDetail->paymentMethod->multiple) {
                    $categoryPaymentDetailMap[$paymentDetail->paymentMethodCategory()][$paymentDetail->paymentMethod->name] = $paymentDetail->total_price;
                } else {
                    $categoryPaymentDetailMap[$paymentDetail->paymentMethodCategory()] = $paymentDetail->total_price;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'deal' => $deal,
                'payment' => $deal->payment,
                'dealGoods' => getKeyMapCollection($deal->dealGoods, 'good_id'),
                'totalPrices' => $service->sumTotals(),
                'categoryPaymentDetailMap' => $categoryPaymentDetailMap,
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
