<?php
namespace App\Services\Ledger;

use App\Enums\PaymentMethodType;
use App\Enums\TransactionType;
use App\Helpers\StdObject;
use App\Models\Agency;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegiChecklistsService
{
    public $dbData;
    function __construct(Request $request)
    {
        $query = Payment::query();

        $this->dbData = $query
            ->when($request->input('entry_date'), function($query, $search) {
                $query->whereDate('payment_date', $search);
            })
            ->when($request->input('entry_date_start'), function($query, $search){
                $datetime = Carbon::parse($search)->startOfDay()->toDateString();
                $query->where('payment_date','>=', $datetime);
            })
            ->when($request->input('entry_date_fin'), function($query, $search){
                $datetime = Carbon::parse($search)->endOfDay()->toDateString();
                $query->where('payment_date','<=', $datetime);
            })
            ->where('office_id', config('const.commons.office_id'))
            ->when($request->input('register'), function($query, $search) {
                $query->where('cash_register_id', $search);
            })->when($request->input('entry_time'), function($query, $search) {
                if($search != 'all') {
                    $hour = (int)explode(':', $search)[0];
                    $query->whereRaw('HOUR(payment_date) = ?', [$hour]);
                }
            })
            ->with(['office', 'deal', 'member', 'cashRegister', 'paymentGoods.dealGood.good', 'paymentDetails.paymentMethod'])
            ->orderBy('payment_date', 'asc')
            ->get();
    }

    /**
     * 事業所別売上
     *
     * @return array<string,OfficeSales>  キーは事業所名
     */
    public function getOfficeSaleTablesData()
    {
        $officeTables = [];

        foreach ($this->dbData as $payment) {
            if($payment->deal->transaction_type == TransactionType::PURCHASE_ONLY->value) {
                continue;
            }

            // 事業所は該当の事業所（サンパーキングの場合は成田）のみ
            // プレミアという代理店だけは別表にしたい
            $agency = $payment->deal->agency;

            $officeTable = null;
            if(isset($agency) && in_array($agency->id, [Agency::NARITA_PREMIUM_ID])) { // プレミアム店
                if(!isset($officeTables[$agency->name])) {
                    $officeTables[$agency->name] = new OfficeSales;
                }
                $officeTable = $officeTables[$agency->name];
            } else { // 事業所
                if(!isset($officeTables[$payment->office->name])) {
                    $officeTables[$payment->office->name] = new OfficeSales;
                }

                $officeTable = $officeTables[$payment->office->name];
            }

            if($payment->price > 0) {
                $officeTable->parkingFeeInAdvance->price += $payment->deal->price + $payment->deal->tax;
                $officeTable->parkingFeeInAdvance->priceExcludingTax += $payment->price;
                $officeTable->parkingFeeInAdvance->count += 1;
                $officeTable->parkingFeeInAdvance->amount += 1;

                $officeTable->total->price += $payment->deal->price + $payment->deal->tax;
            }


            foreach ($payment->paymentGoods as $paymentGood){
                $dealGood = $paymentGood->dealGood()->withTrashed()->first();
                if(!isset($officeTable->goodsSales->rows[$dealGood->good_id])) {
                    $officeTable->goodsSales->rows[$dealGood->good_id] = new TableRow([
                        'itemName' => $dealGood->good->name,
                        'count' => 0,
                        'amount' => 0,
                        'price' => 0,
                        'priceExcludingTax' => 0,
                    ]);
                }

                $goodsRow = $officeTable->goodsSales->rows[$dealGood->good_id];
                $goodsRow->id = $dealGood->good_id;
                $goodsRow->count += 1;
                $goodsRow->amount += $paymentGood->num;
                $goodsRow->price += $paymentGood->total_price + $paymentGood->total_tax;
                $goodsRow->priceExcludingTax += $paymentGood->total_price;

                // $officeTable->goodsSales->total->price += $paymentGood->total_price + $paymentGood->total_tax;

                $officeTable->total->price += $paymentGood->total_price + $paymentGood->total_tax;
            }
        }


        return $officeTables;
    }

    /**
     * 売上 合計
     *
     * @return TotalSales
     */
    public function getTotalSalesTableData()
    {
        $totalSalesTable = new TotalSales($this->getGoodsTableData());

        foreach ($this->dbData as $payment) {
            if($payment->price > 0) {
                $totalSalesTable->parkingFeeInAdvance->price += $payment->deal->price + $payment->deal->tax;
                $totalSalesTable->parkingFeeInAdvance->priceExcludingTax += $payment->price;
                $totalSalesTable->parkingFeeInAdvance->count += 1;
                $totalSalesTable->parkingFeeInAdvance->amount += 1;

                $totalSalesTable->total->price += $payment->deal->price + $payment->deal->tax;
            }

        }

        return $totalSalesTable;
    }


    public function getGoodsTableData(bool $purchaseOnly = false)
    {
        $goodsTable = new GoodsSales;

        foreach ($this->dbData as $payment) {

            if($purchaseOnly && $payment->deal->transaction_type != TransactionType::PURCHASE_ONLY->value) {
                continue;
            }

            foreach ($payment->paymentGoods as $paymentGood){
                $dealGood = $paymentGood->dealGood()->withTrashed()->first();
                if(!isset($goodsTable->rows[$dealGood->good_id])) {
                    $goodsTable->rows[$dealGood->good_id] = new TableRow([
                        'itemName' => $dealGood->good->name,
                        'count' => 0,
                        'amount' => 0,
                        'price' => 0,
                        'priceExcludingTax' => 0,
                    ]);
                }

                $goodsRow = $goodsTable->rows[$dealGood->good_id];
                $goodsRow->id = $dealGood->good_id;
                $goodsRow->count += 1;
                $goodsRow->amount += $paymentGood->num;
                $goodsRow->price += $paymentGood->total_price + $paymentGood->total_tax;
                $goodsRow->priceExcludingTax += $paymentGood->total_price;

                $goodsTable->total->price += $paymentGood->total_price + $paymentGood->total_tax;
            }
        }

        return $goodsTable;
    }

    public function getCashTableData(Request $request)
    {
        $cashTable = new CashAccounting;

        // 現金  処理前の現金残高
        $cashTable->cash->price =  0;
        $cashTable->cash->count = 0;

        foreach ($this->dbData as $payment) {
            // 釣り銭
            if($payment->cash_change > 0) {
                $cashTable->change->price += $payment->cash_change;
            }
            // 出金 別の用途での出金処理 未実装
            // 両替：新システム未実装

            $paidAmount = 0;
            foreach ($payment->paymentDetails as $paymentDetail) {
                /** @var PaymentDetail $paymentDetail */
                $paymentMethodType = PaymentMethodType::tryFrom($paymentDetail->paymentMethod->type);
                if (!$paymentMethodType || $paymentMethodType != PaymentMethodType::CASH) {
                    continue;
                }
                $paidAmount += $paymentDetail->total_price;
            }

            // 入金
            if($paidAmount > 0) {
                $cashTable->deposit->price += $paidAmount;
                $cashTable->deposit->count += 1;
            }

            // 現金支払い額 - 釣り銭
            $cashFlow = $paidAmount - $payment->cash_change;

            // 現金残高
            $cashTable->cashBalance->price += $cashFlow;
        }

        return $cashTable;
    }

    public function getCreditsTableData()
    {
        $creditsTable = new CreditSales;

        foreach ($this->dbData as $payment) {

            foreach ($payment->paymentDetails as $paymentDetail) {
                /** @var PaymentDetail $paymentDetail */
                $paymentMethodType = PaymentMethodType::tryFrom($paymentDetail->paymentMethod->type);
                if (!$paymentMethodType || $paymentMethodType != PaymentMethodType::CREDIT) {
                    continue;
                }

                if(!isset($creditsTable->rows[$paymentDetail->paymentMethod->id])) {
                    $creditsTable->rows[$paymentDetail->paymentMethod->id] = new TableRow([
                        'itemName' => $paymentDetail->paymentMethod->name,
                        'count' => 0,
                        'amount' => 0,
                        'price' => 0,
                    ]);
                }

                $row = $creditsTable->rows[$paymentDetail->paymentMethod->id];

                // 処理回数
                $row->count += 1;
                $creditsTable->total->count += 1;

                // 数量 処理回数(total_price >= 0) - 返金回数(total_price < 0)
                if($paymentDetail->total_price >= 0) {
                    $row->amount += 1;
                    $creditsTable->total->amount += 1;
                } else {
                    $row->amount -= 1;
                    $creditsTable->total->amount -= 1;
                }

                $row->price += $paymentDetail->total_price;
                $creditsTable->total->price += $paymentDetail->total_price;
            }
        }

        return $creditsTable;
    }

    public function getGiftCertificatesTableData()
    {
        $giftCertificatesTable = new GiftCertificatesSales;

        foreach ($this->dbData as $payment) {

            foreach ($payment->paymentDetails as $paymentDetail) {
                /** @var PaymentDetail $paymentDetail */
                $paymentMethodType = PaymentMethodType::tryFrom($paymentDetail->paymentMethod->type);
                if (!$paymentMethodType || $paymentMethodType != PaymentMethodType::GIFT_CERTIFICATE) {
                    continue;
                }

                // 処理回数
                $giftCertificatesTable->total->count += 1;

                // 数量 処理回数(total_price >= 0) - 返金回数(total_price < 0)
                if($paymentDetail->total_price >= 0) {
                    $giftCertificatesTable->total->amount += 1;
                } else {
                    $giftCertificatesTable->total->amount -= 1;
                }
                $giftCertificatesTable->total->price += $paymentDetail->total_price;
            }
        }

        return $giftCertificatesTable;
    }

    public function getCouponTableData()
    {
        $couponTable = new CouponSales;

        foreach ($this->dbData as $payment) {

            foreach ($payment->paymentDetails as $paymentDetail) {
                /** @var PaymentDetail $paymentDetail */
                $paymentMethodType = PaymentMethodType::tryFrom($paymentDetail->paymentMethod->type);
                if (!$paymentMethodType || $paymentMethodType != PaymentMethodType::COUPON) {
                    continue;
                }

                if(!isset($couponTable->rows[$paymentDetail->coupon_id])) {
                    $couponTable->rows[$paymentDetail->coupon_id] = new TableRow([
                        'itemName' => $paymentDetail->coupon->name,
                        'count' => 0,
                        'amount' => 0,
                        'price' => 0,
                    ]);
                }

                $row = $couponTable->rows[$paymentDetail->coupon_id];

                // 処理回数
                $row->count += 1;
                $couponTable->total->count += 1;

                // 数量 処理回数(total_price >= 0) - 返金回数(total_price < 0)
                if($paymentDetail->total_price >= 0) {
                    $row->amount += 1;
                    $couponTable->total->amount += 1;
                } else {
                    $row->amount -= 1;
                    $couponTable->total->amount -= 1;
                }

                $row->price += $paymentDetail->total_price;
                $couponTable->total->price += $paymentDetail->total_price;
            }
        }

        return $couponTable;
    }
}


class TableRow extends StdObject
{
    // ID
    public $id;
    // 項目名
    public $itemName;
    // 処理回数
    public $count;
    // 数量
    public $amount;
    // 金額
    public $price;
    // 税抜金額
    public $priceExcludingTax;
}
class PaymentRow extends StdObject
{
    // ID
    public $id;
    // 項目名
    public $itemName;
    // 処理回数
    public $count;
    // 数量
    public $amount;
    // 金額
    public $price;
}

class OfficeSales
{
    /** @var TableRow 駐車料金（前払い） */
    public $parkingFeeInAdvance;
    /** @var TableRow 駐車料金（後払い） */
    public $parkingFeeLater;
    /** @var TableRow 駐車料金（追加／返金） */
    public $parkingFeeAddReturn;
    /** @var TableRow 一日利用（チケット） */
    // public $oneDayUseWithTicket;
    /** @var TableRow 一日利用（チケット以外） */
    // public $oneDayUseWithoutTicket;

    /** @var GoodsSales 商品売上 */
    public $goodsSales;
    /** @var TableRow 合計 */
    public $total;

    function __construct()
    {
        $this->parkingFeeInAdvance = new TableRow([
            'itemName' => '駐車料金',
            'count' => 0,
            'amount' => 0,
            'price' => 0,
            'priceExcludingTax' => 0,
        ]);
        $this->total = new TableRow([
            'itemName' => '合計',
            'price' => 0,
        ]);
        $this->goodsSales = new GoodsSales;
    }
}

class TotalSales
{
    /** @var TableRow 駐車料金（前払い） */
    public $parkingFeeInAdvance;
    /** @var TableRow 駐車料金（後払い） */
    // public $parkingFeeLater;
    /** @var TableRow 駐車料金（追加／返金） */
    // public $parkingFeeAddReturn;
    /** @var TableRow 一日利用（チケット） */
    // public $oneDayUseWithTicket;
    /** @var TableRow 一日利用（チケット以外） */
    // public $oneDayUse;
    /** @var GoodsSales 商品売上 */
    public $goodsSales;
    /** @var TableRow 合計 */
    public $total;

    function __construct(GoodsSales $goodsSales)
    {
        $this->parkingFeeInAdvance = new TableRow([
            'itemName' => '駐車料金（前払い）',
            'count' => 0,
            'amount' => 0,
            'price' => 0,
            'priceExcludingTax' => 0,
        ]);
        $this->total = new TableRow([
            'itemName' => '合計',
            'price' => $goodsSales? $goodsSales->total->price : 0,
        ]);
        $this->goodsSales = $goodsSales;
    }
}

class GoodsSales
{
    /** @var array<string,TableRow> キーは商品ID */
    public $rows = [];
    /** @var TableRow 合計 */
    public $total;

    function __construct()
    {
        $this->total = new TableRow([
            'itemName' => '合計',
            'price' => 0,
        ]);
    }
}

class CashAccounting
{
    /** @var PaymentRow 現金 処理前の現金残高 */
    public $cash;
    /** @var PaymentRow 入金 */
    public $deposit;
    /** @var PaymentRow 出金 */
    // public $withdrawal;
    /** @var PaymentRow 両替 */
    // public $exchange;
    /** @var PaymentRow 釣り銭 */
    public $change;
    /** @var PaymentRow 現金残高 */
    public $cashBalance;

    function __construct()
    {
        $this->cash = new PaymentRow([
            'itemName' => '現金',
            'count' => 0,
            'price' => 0,
        ]);
        $this->deposit = new PaymentRow([
            'itemName' => '入金',
            'count' => 0,
            'price' => 0,
        ]);
        $this->change = new PaymentRow([
            'itemName' => '釣り銭',
            'price' => 0,
        ]);
        $this->cashBalance = new PaymentRow([
            'itemName' => '現金残高',
            'price' => 0,
        ]);

    }
}

class CreditSales
{
    // PaymentRow.amount (件数) の意味は
    // 処理回数(total_price >= 0 のケース) - 返金回数(total_price < 0 のケース)

    /** @var PaymentRow[] */
    public $rows = [];
    /** @var PaymentRow 合計 */
    public $total;

    function __construct()
    {
        $this->total = new PaymentRow([
            'itemName' => '合計',
            'count' => 0,
            'amount' => 0,
            'price' => 0,
        ]);
    }
}

class CouponSales
{
    // PaymentRow.amount (数量) の意味は
    // 処理回数(total_price >= 0 のケース) - 返金回数(total_price < 0 のケース)

    /** @var PaymentRow[] */
    public $rows = [];
    /** @var PaymentRow 合計 */
    public $total;

    function __construct()
    {
        $this->total = new PaymentRow([
            'itemName' => '合計',
            'count' => 0,
            'amount' => 0,
            'price' => 0,
        ]);
    }
}

class GiftCertificatesSales
{
    // PaymentRow.amount (件数) の意味は
    // 処理回数(total_price >= 0 のケース) - 返金回数(total_price < 0 のケース)

    /** @var PaymentRow 合計 */
    public $total;

    function __construct()
    {
        $this->total = new PaymentRow([
            'itemName' => '合計',
            'amount' => 0,
            'price' => 0,
        ]);
    }
}
