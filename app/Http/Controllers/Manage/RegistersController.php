<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DealStatus;
use App\Enums\PaymentMethodType;
use App\Enums\TransactionType;
use App\Exceptions\PrinterPrintException;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\RegisterStoreRequest;
use App\Jobs\ProcessPrintJob;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PaymentMethod;
use App\Services\Deal\DealGoodsService;
use App\Services\Deal\DealService;
use App\Services\Deal\ExtraPaymentManager;
use App\Services\Deal\PaymentService;
use App\Services\Printers\LabelPrintable;
use App\Services\Printers\ReceiptPrintable;
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
        $rewindDeal = session()->get('rewind_deal');
        if($rewindDeal and $rewindDeal->id == $request->input('deal_id')) {
            // セッションから削除
            session()->forget('rewind_deal');
        }
        $transactionType = TransactionType::PURCHASE_ONLY->value;

        if( $request->input('deal_id')) {
            $deal = Deal::where('id', $request->input('deal_id'))->select('id', 'transaction_type')->first();
            if($deal && $deal->transaction_type != TransactionType::PURCHASE_ONLY->value) {
                $transactionType = $deal->transaction_type;
            }
        }

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
            'transactionType' => $transactionType,
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
            $service = new PaymentService($request->input('deal_id'), $request->all());

            $rewindDeal = session()->get('rewind_deal');
            if($rewindDeal and $rewindDeal->id === $service->deal->id) {
                // 取引情報を更新してからセッションから削除
                $rewindDeal->load('dealGoods');
                $service->deal = $rewindDeal;
                session()->forget('rewind_deal');
            }

            DB::transaction(function () use($service) {
                $service->save();

                // ステータス更新 入庫済み
                if($service->deal->status != DealStatus::UNLOADED->value) {
                    $service->deal->status = DealStatus::LOADED->value;
                }
                $service->deal->overdue = false; // 延長フラグをリセット
                $service->deal->save();
            });
            $this->printAll($service->deal, $service->payment);
        } catch (PrinterPrintException $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect(route('manage.deals.show', [$request->deal_id]))->with('failure', '決済処理は完了しましたが、ラベル・領収書印刷処理に失敗しました。印刷処理をやり直してください。');
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return redirect()->back()->with('failure', '決済処理に失敗しました。決済手続きをやり直してください。');
        }
        // return redirect(route('manage.receipts.show', [$request->deal_id]));
        return redirect(route('manage.deals.show', [$request->deal_id]))->with('success', '決済処理を完了し、印刷ジョブを開始しました。');
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
     * Update the specified resource in storage.
     */
    public function recalcDealPrices(Request $request, string $id)
    {

        try {
            $deal = Deal::findOrFail($id);

            if($deal->transaction_type == TransactionType::PURCHASE_ONLY->value) {
                throw new \Exception('商品購入のみでは、日時戻しはできません。');
            }

            $manager = new ExtraPaymentManager($deal);
            DB::transaction(function () use($manager, $request) {
                $manager->recalcDealPricesOnRewindDate($request->input('entry_date'), false);
                session()->put('rewind_deal', $manager->deal);
            });
        } catch (\Throwable $th) {
            Log::error('エラー内容：' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => '取引商品の更新に失敗しました。' . $th->getMessage(),
             ]);
        }

        $service = new DealGoodsService($manager->deal);
        return response()->json([
            'success' => true,
            'data' => [
                'deal' => $manager->deal,
                'dealGoods' => getKeyMapCollection($manager->deal->dealGoods, 'good_id'),
                'totalPrices' => $service->sumTotals(),
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

    /**
     * 領収書とラベルをまとめて印刷処理
     * キューに投入
     *
     * @param Deal $deal
     * @param Payment $payment
     * @return void
     * @throws PrinterPrintException
     */
    private function printAll(Deal $deal, Payment $payment)
    {
        try {
            // プリンタ接続情報を設定
            // 領収書とラベル、2つの設定を用意
            $labelPrinterConfig = config("services.printers.label_printer"); // ラベルプリンタ
            // $labelPrinterConfig = config("services.printers.receipt_printer"); // debug用

            $receiptPrinterConfig = config("services.printers.receipt_printer");

            // 印刷物（Printable）のインスタンスを作成
            $labelPrintable = new LabelPrintable($deal, [
                // mPDF用に上書き設定
                'format' => [80, 48], // [幅mm, 高さmm] 例：48mm x 80mmラベル
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'margin_right' => 0,
            ]);
            $receiptPrintable = new ReceiptPrintable($deal, $payment);
            // 印刷処理をキューに投入
            ProcessPrintJob::dispatch($labelPrinterConfig, $labelPrintable);
            ProcessPrintJob::dispatch($receiptPrinterConfig, $receiptPrintable, 'puppeteer');

        } catch (\Throwable $th) {
            throw new PrinterPrintException();
        }
    }
}
