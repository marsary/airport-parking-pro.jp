<?php
namespace App\Services\Ledger;

use App\Enums\PaymentMethodType;
use App\Enums\TransactionType;
use App\Helpers\StdObject;
use App\Models\Agency;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class RegiChecklistsService
{
    public $dbData;
    function __construct(Request $request)
    {
        $query = Payment::query();

        $this->dbData = $query->whereDate('payment_date', $request->entry_date)
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
                if(!isset($officeTable->goodsSales->rows[$paymentGood->dealGood->good_id])) {
                    $officeTable->goodsSales->rows[$paymentGood->dealGood->good_id] = new TableRow([
                        'itemName' => $paymentGood->dealGood->good->name,
                        'count' => 0,
                        'amount' => 0,
                        'price' => 0,
                        'priceExcludingTax' => 0,
                    ]);
                }

                $goodsRow = $officeTable->goodsSales->rows[$paymentGood->dealGood->good_id];
                $goodsRow->id = $paymentGood->dealGood->good_id;
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
                if(!isset($goodsTable->rows[$paymentGood->dealGood->good_id])) {
                    $goodsTable->rows[$paymentGood->dealGood->good_id] = new TableRow([
                        'itemName' => $paymentGood->dealGood->good->name,
                        'count' => 0,
                        'amount' => 0,
                        'price' => 0,
                        'priceExcludingTax' => 0,
                    ]);
                }

                $goodsRow = $goodsTable->rows[$paymentGood->dealGood->good_id];
                $goodsRow->id = $paymentGood->dealGood->good_id;
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
    }

    public function getGiftCertificatesTableData()
    {
    }

    public function getCouponTableData()
    {
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
