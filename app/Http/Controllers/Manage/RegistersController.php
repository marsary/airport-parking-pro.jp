<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Enums\PaymentMethod\AdjustmentType;
use App\Enums\PaymentMethod\DiscountType;
use App\Enums\PaymentMethodType;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\RegisterStoreRequest;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\PaymentDetail;
use App\Models\PaymentMethod;
use App\Services\Deal\DealGoodsService;
use App\Services\Deal\DealService;
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
        $today = Carbon::today();
        $coupons = Coupon::whereDate('start_date','<=', $today->toDateString())
            ->whereDate('end_date','>', $today->toDateString())
            ->where('office_id', config('const.commons.office_id'))
            ->get();
        $goodsMap = getKeyMapCollection($goods);
        return view('manage.registers.index', [
            'goodCategories' => $goodCategories,
            'goodsMap' => $goodsMap,
            'coupons' => $coupons,
            'paymentMethodCategoryMap' => PaymentMethod::getIdNameMapGroupedByCategory(),
            'dealId' => $request->input('deal_id'),
            'getDiscountTypeMap' => PaymentDetail::getDiscountTypeMap(),
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

                // ステータス更新 入庫済み
                if($service->deal->status != DealStatus::UNLOADED->value) {
                    $service->deal->status = DealStatus::LOADED->value;
                    $service->deal->save();
                }
            });
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '決済処理に失敗しました。決済手続きをやり直してください。');
        }
        return redirect(route('manage.receipts.show', [$request->deal_id]));
        // return redirect(route('manage.deals.show', [$request->deal_id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if($id == 0) {// 商品購入のみ
            $deal = DealService::makePurchaseOnly();
        } else {
            $deal = Deal::findOrFail($id);
        }

        $service = new DealGoodsService($deal);
        $categoryPaymentDetailMap = [];
        $appliedCoupons = [];
        $appliedDiscounts = [];
        $appliedAdjustments = [];
        if($deal->payment?->paymentDetails) {
            foreach ($deal->payment->paymentDetails as $paymentDetail) {
                if($paymentDetail->coupon()->exists()) {
                    $appliedCoupons[$paymentDetail->coupon->id] = $paymentDetail->total_price;
                    continue;
                }
                if(isset($paymentDetail->discount_type)) {
                    $paymentMethodType = $paymentDetail->paymentMethodCategory();
                    switch ($paymentMethodType) {
                        case PaymentMethodType::DISCOUNT->symbol():
                            /** @var DiscountType|null */
                            $discountType = $paymentDetail->paymentMethodDiscountType();
                            if($discountType) {
                                $appliedDiscounts[$discountType->label()] = $paymentDetail->total_price;
                                continue 2;
                            }
                        case PaymentMethodType::ADJUSTMENT->symbol():
                            /** @var AdjustmentType|null */
                            $adjustmentType = $paymentDetail->paymentMethodAdjustmentType();
                            if($adjustmentType) {
                                $appliedAdjustments[$adjustmentType->label()] = $paymentDetail->total_price;
                                continue 2;
                            }

                        default:
                            break;
                    }
                }
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
                'appliedCoupons' => !empty($appliedCoupons) ? $appliedCoupons:null,
                'appliedDiscounts' =>  !empty($appliedDiscounts) ? $appliedDiscounts:null,
                'appliedAdjustments' =>  !empty($appliedAdjustments) ? $appliedAdjustments:null,
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
