<?php
namespace App\Services\Ledger;

use App\Enums\DealStatus;
use App\Enums\PaymentMethodType;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class RegiSalesAccountBooksService
{
    public $office;

    function __construct()
    {
        $this->office = myOffice();
    }

    public function getSalesTableData(Request $request)
    {
        $dbData = $this->fetchData($request);

        $tableData = ['rows' => [], 'bottomLine' => null];
        $bottomLine = new RegiSalesBottomLineRow();

        foreach ($dbData as $payment) {
            $row = new RegiSalesAccountBooksRow();
            $row->dealId = $payment->deal->id;
            $row->paymentTime = $payment->payment_date;
            $row->memberName = $payment->member?->name ? $payment->member->name : $payment->deal->name;
            $row->isUpdated = ($payment->created_at == $payment->updated_at);
            $row->isUnloaded = $payment->deal->status == DealStatus::UNLOADED->value;
            $row->officeName = $this->office->short_name;
            $row->days = $payment->days;
            $row->agencyName = $payment->deal->agency?->name;
            $row->dscRate = $payment->deal->dsc_rate;
            $row->dealPrice = $payment->deal->price;
            $row->dealTax = $payment->deal->tax;

            $this->setCategorizedGoodPrices($payment, $row);

            $row->dealTotalPrice = $payment->deal->total_price;
            $row->cashEnter = $payment->cash_enter;
            $row->cashChange = $payment->cash_change;
            $row->totalPay = $payment->total_pay;

            $this->setPaymentMethodPrices($payment, $row);

            $row->userName = $payment->user_name;

            $bottomLine->sumUp($row);

            $tableData['rows'][] = $row;
        }

        $tableData['bottomLine'] = $bottomLine;

        return $tableData;
    }

    private function setCategorizedGoodPrices(Payment $payment, RegiSalesAccountBooksRow $row)
    {
        $waxPrices = [];
        $insurancePrices = [];
        $otherPrices = [];

        foreach ($payment->paymentGoods as $paymentGood) {
            switch($paymentGood->goodCategory->name)
            {
                case "洗車":
                    $this->accumulateValueByKey($waxPrices, $paymentGood->name, $paymentGood->total_price + $paymentGood->total_tax);
                    break;
                case "保険":
                    $this->accumulateValueByKey($insurancePrices, $paymentGood->name, $paymentGood->total_price + $paymentGood->total_tax);
                    break;
                default:
                    $this->accumulateValueByKey($otherPrices, $paymentGood->name, $paymentGood->total_price + $paymentGood->total_tax);
                    break;
            }
        }

        $row->waxPrices = $waxPrices;
        $row->insurancePrices = $insurancePrices;
        $row->otherPrices = $otherPrices;
    }

    private function setPaymentMethodPrices(Payment $payment, RegiSalesAccountBooksRow $row)
    {
        $cash = 0;
        $credits = [];
        $coupons = [];
        $others = (new RegiSalesAccountBooksRow)->others;

        foreach ($payment->paymentDetails as $paymentDetail) {
            /** @var PaymentDetail $paymentDetail */
            $paymentMethodType = PaymentMethodType::tryFrom($paymentDetail->paymentMethod->type);
            if (!$paymentMethodType) {
                continue;
            }

            switch($paymentMethodType)
            {
                case PaymentMethodType::CASH:
                    $cash += $paymentDetail->total_price;
                    break;
                case PaymentMethodType::CREDIT:
                    $this->accumulateValueByKey($credits, $paymentDetail->paymentMethod->name, $paymentDetail->total_price);
                    break;
                case PaymentMethodType::E_MONEY:
                case PaymentMethodType::QR_CODE:
                case PaymentMethodType::GIFT_CERTIFICATE:
                case PaymentMethodType::TRAVEL_ASSIST:
                case PaymentMethodType::VOUCHER:
                case PaymentMethodType::OTHER:
                case PaymentMethodType::DISCOUNT:
                case PaymentMethodType::ADJUSTMENT:
                    $this->accumulateValueByKey($others, $paymentMethodType->label(), $paymentDetail->total_price);
                    break;
                case PaymentMethodType::COUPON:
                    $coupon = $paymentDetail->coupon;
                    if($coupon) {
                        $this->accumulateValueByKey($coupons, $coupon->name, $paymentDetail->total_price);
                    }
                    break;
                default:
                    break;
            }
        }


        $row->cash = $cash;
        $row->credits = $credits;
        $row->coupons = $coupons;
        $row->others = $others;
    }

    private function fetchData(Request $request)
    {
        $query = Payment::query();

        $data = $query->whereDate('payment_date', $request->entry_date)
            ->where('office_id', config('const.commons.office_id'))
            ->when($request->input('register'), function($query, $search) {
                $query->where('cash_register_id', $search);
            })->when($request->input('entry_time'), function($query, $search) {
                if($search != 'all') {
                    $hour = (int)explode(':', $search)[0];
                    $query->whereRaw('HOUR(payment_date) = ?', [$hour]);
                }
            })
            ->with(['deal', 'member', 'cashRegister', 'paymentGoods.goodCategory', 'paymentDetails.paymentMethod'])
            ->orderBy('payment_date', 'asc')
            ->get();


        return $data;
    }

    private function accumulateValueByKey(&$array, $key, $value)
    {
        if(isset($array[$key])) {
            $array[$key] += $value;
        } else {
            $array[$key] = $value;
        }
    }
}

class RegiSalesAccountBooksRow
{
    // 受付ID
    public $dealId;
    // 時刻
    public $paymentTime;
    // 氏名
    public $memberName;
    // 修
    public $isUpdated;
    // 帰
    public $isUnloaded;
    // 事
    public $officeName;
    // 日
    public $days;
    // 代理店
    public $agencyName;
    // 率
    public $dscRate = 0;
    // 駐車
    public $dealPrice = 0;
    public $dealTax = 0;
    // 券
    public $discountTicketName = "";
    // マイル
    public $mile = "";
    // WAX
    public $waxPrices = [];
    // 保険
    public $insurancePrices = [];
    // 他
    public $otherPrices = [];
    // 合計
    public $dealTotalPrice = 0;

    // 現金
    public $cash = 0;
    // 預り
    public $cashEnter = 0;
    // 釣り
    public $cashChange = 0;
    /** @var array<string,int> クレ */
    public $credits = [];
    /** @var array<string,int> クーポ */
    // クーポ
    public $coupons = [];
    // 前力
    // public $prepaidCard = null;
    // SBI
    // public $sbi = null;
    // 他
    public $others = [
        // 電子マネー
        "電子マネー" => 0,
        // QRコード
        "QRコード" => 0,
        // 商品券
        "商品券" => 0,
        // 旅行支援
        "旅行支援" => 0,
        // バウチャー
        "バウチャー" => 0,
        // その他
        "その他" => 0,
        // 値引き
        "値引き" => 0,
        // 調整
        "調整" => 0,
    ];
    // 合計
    public $totalPay = 0;
    // 担当
    public $userName;
}

class RegiSalesBottomLineRow
{
    // 駐車
    public $dealPriceTaxed = 0;
    // WAX
    public $waxPrice = 0;
    // 保険
    public $insurancePrice = 0;
    // 他
    public $otherPrice = 0;
    // 合計
    public $dealTotalPrice = 0;

    // 現金
    public $cash = 0;
    // クレ
    public $credits = 0;
    // クーポ
    public $coupons = 0;
    // 他
    public $others = 0;
    // 合計
    public $totalPay = 0;

    public function sumUp(RegiSalesAccountBooksRow $row)
    {
        $this->dealPriceTaxed += $row->dealPrice + $row->dealTax;
        $this->waxPrice += array_sum($row->waxPrices);
        $this->insurancePrice += array_sum($row->insurancePrices);
        $this->otherPrice += array_sum($row->otherPrices);
        $this->dealTotalPrice += $row->dealTotalPrice;
        $this->cash += $row->cash;
        $this->totalPay += $row->totalPay;


        $this->credits += array_sum($row->credits);
        $this->coupons += array_sum($row->coupons);
        $this->others += array_sum($row->others);
    }
}
