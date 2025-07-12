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
            $row->dealId = $payment->deal->id;
            $row->paymentTime = $payment->payment_date;
            $row->memberName = $payment->member->name;
            $row->isUpdated = ($payment->created_at == $payment->updated_at);
            $row->officeName = $this->office->short_name;
            $row->days = $payment->days;
            $row->agencyName = $payment->deal->agency?->name;
            $row->dscRate = $payment->deal->dsc_rate;
            $tableData['rows'][] = $row;
        }

        $tableData['bottomLine'] = $bottomLine;

        return $tableData;
    }



    private function fetchData(Request $request)
    {
        $query = Payment::query();

        $data = $query->whereDate('payment_date', $request->entry_date)
            ->where('office_id', config('const.commons.office_id'))
            ->orderBy('payment_date', 'asc')
            ->get();


        return $data;
    }

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

    public function sumUp($row)
    {
        $this->dealPriceTaxed += $row->dealPrice + $row->dealTax;
        $this->dealTotalPrice += $row->dealTotalPrice;
        $this->cash += $row->cash;
        $this->totalPay += $row->totalPay;
    }
}
