<?php
namespace App\Services\Ledger;

use App\Enums\PaymentMethodType;
use App\Enums\TaxType;
use App\Helpers\StdObject;
use App\Models\Agency;
use App\Services\Ledger\Repositories\PaymentSummaryRepositoryInterface;
use Illuminate\Http\Request;

class RegiPaymentSummariesService
{
    public $dbData;
    public $office;
    private $paymentSummaryRepository;

    function __construct(PaymentSummaryRepositoryInterface $paymentSummaryRepository)
    {
        $this->office = myOffice();
        $this->paymentSummaryRepository = $paymentSummaryRepository;
    }


    /**
     * 統括表（店舗用）
     *
     * @param Request $request
     * @return array<string,array<string,int>>
     */
    public function getLoadUnloadCounts(Request $request)
    {
        // 取扱台数
        $totalCounts = [
            'load' => 0,
            'unload' => 0,
            'pending' => 0,
            'loaded' => 0,
            'long_term' => 0,
        ];
        // 入庫詳細
        $loadCounts = [
            $this->office->name => 0,
            'プレ' => 0
        ];
        // 出庫詳細
        $unloadCounts = [
            $this->office->name => 0,
            'プレ' => 0
        ];
        // 保留明細
        $pendingCounts = [
            $this->office->name => 0,
            'プレ' => 0
        ];
        // 長期

        // 在庫明細
        $loadedCounts = [
            $this->office->name => 0,
            'プレ' => 0
        ];

        $results = $this->paymentSummaryRepository->getLoadUnloadCounts($request);

        // 入庫
        foreach ($results['load'] as $record) {
            $totalCounts['load'] += $record->count;
            if($record->agency_id == Agency::NARITA_PREMIUM_ID) {
                $loadCounts['プレ'] += $record->count;
            } else {
                $loadCounts[$this->office->name] += $record->count;
            }
        }
        // 出庫
        foreach ($results['unload'] as $record) {
            $totalCounts['unload'] += $record->count;
            if($record->agency_id == Agency::NARITA_PREMIUM_ID) {
                $unloadCounts['プレ'] += $record->count;
            } else {
                $unloadCounts[$this->office->name] += $record->count;
            }
        }
        // 保留
        foreach ($results['pending'] as $record) {
            $totalCounts['pending'] += $record->count;
            if($record->agency_id == Agency::NARITA_PREMIUM_ID) {
                $pendingCounts['プレ'] += $record->count;
            } else {
                $pendingCounts[$this->office->name] += $record->count;
            }
        }
        // 長期 保留のうち、long_hold_flg = true のもの

        // 在庫
        foreach ($results['loaded'] as $record) {
            $totalCounts['loaded'] += $record->count;
            if($record->agency_id == Agency::NARITA_PREMIUM_ID) {
                $loadedCounts['プレ'] += $record->count;
            } else {
                $loadedCounts[$this->office->name] += $record->count;
            }
        }


        // 一日利用 スキップ


        return [
            'totalCounts' => $totalCounts,
            'loadCounts' => $loadCounts,
            'unloadCounts' => $unloadCounts,
            'pendingCounts' => $pendingCounts,
            'loadedCounts' => $loadedCounts,
        ];
    }

    public function getPaymentMethodTable(Request $request)
    {
        $paymentMethodTable = new SummaryTable;
        // レジ取扱額
        $paymentMethodTableData = $this->paymentSummaryRepository->getPaymentMethodTableData($request);

        foreach ($paymentMethodTableData as $row) {
            $paymentMethodTable->addToRow(
                PaymentMethodType::tryFrom($row->type)->label(),
                $row->count,
                $row->price
            );
        }

        return $paymentMethodTable;
    }

    public function getRegiPaymentGoodsTables(Request $request)
    {
        // レジ取扱明細（単品）
        $paymentGoodsTable = new SummaryTable;
        // レジ取扱明細（事業所）
        $paymentGoodsOfficeTable = new SummaryTable;
        // レジ取扱明細（プレ）
        $paymentGoodsPreTable = new SummaryTable;
        // 現金払い
        $paymentChangeTable = new SummaryTable;

        $results = $this->paymentSummaryRepository->getPaymentGoodsData($request);
        $parkingData = $results['parkingData'];
        $goodsData = $results['goodsData'];
        $cashData = $results['cashData'];

        foreach ($parkingData as $parkingRow) {
            $price = $parkingRow->price + roundTax((TaxType::TEN_PERCENT->rate() ?? 0) * $parkingRow->price);
            $paymentGoodsTable->addToRow(
                '駐車料金',
                $parkingRow->count,
                $price
            );
            if($parkingRow->agency_id == Agency::NARITA_PREMIUM_ID) {
                $paymentGoodsPreTable->addToRow(
                    '駐車料金',
                    $parkingRow->count,
                    $price
                );
            } else {
                $paymentGoodsOfficeTable->addToRow(
                    '駐車料金',
                    $parkingRow->count,
                    $price
                );
            }
        }

        foreach ($goodsData as $record) {
            $paymentGoodsTable->addToRow(
                $record->category_name,
                $record->count,
                $record->price,
            );
            if($record->agency_id == Agency::NARITA_PREMIUM_ID) {
                $paymentGoodsPreTable->addToRow(
                    $record->category_name,
                    $record->count,
                    $record->price,
                );
            } else {
                $paymentGoodsOfficeTable->addToRow(
                    $record->category_name,
                    $record->count,
                    $record->price,
                );
            }
        }

        // 現金払い：自分の事業所とプレについて、レジ出金額を集計
        foreach ($cashData as $row) {
            if($row->agency_id == Agency::NARITA_PREMIUM_ID) {
                $paymentChangeTable->addToRow(
                    'プレミア',
                    $row->count,
                    $row->price
                );
            } else {
                $paymentChangeTable->addToRow(
                    $this->office->name,
                    $row->count,
                    $row->price
                );
            }
        }

        return [
            'paymentGoodsTable' => $paymentGoodsTable,
            'paymentGoodsOfficeTable' => $paymentGoodsOfficeTable,
            'paymentGoodsPreTable' => $paymentGoodsPreTable,
            'paymentChangeTable' => $paymentChangeTable,
        ];
    }

    public function getCreditTableOfficeData(Request $request)
    {
        $creditTable = new SummaryTable;

        $paymentDetailData = $this->paymentSummaryRepository->getCreditTableOfficeData($request);



        foreach ($paymentDetailData as $row) {
            if($row->agency_id == Agency::NARITA_PREMIUM_ID) {
                $creditTable->addToRow(
                    'プレミア',
                    $row->count,
                    $row->price
                );
            } else {
                $creditTable->addToRow(
                    $this->office->name,
                    $row->count,
                    $row->price
                );
            }
        }


        return $creditTable;
    }
}


class SummaryTable
{
    /** @var array<string,SummaryRow> */
    public $rows = [];

    /** @var SummaryRow */
    public $total;

    function __construct()
    {
        $this->total = new SummaryRow([
            'itemName' => '合計',
            'count' => 0,
            'price' => 0,
        ]);
    }

    public function createRow($itemName, $count, $price, $id = null)
    {
        $row = new SummaryRow([
            'id' => $id ?? $itemName,
            'itemName' => $itemName,
            'count' => $count,
            'price' => $price,
        ]);

        $this->rows[$itemName] = $row;

        $this->total->count += $count;
        $this->total->price += $price;
    }

    public function addToRow($itemName, $count, $price, $calcTotal = true, $id = null)
    {
        if(!isset($this->rows[$itemName])) {
            $this->createRow(
                $itemName,
                0,
                0,
                $id,
            );
        }

        $this->rows[$itemName]->count += $count;
        $this->rows[$itemName]->price += $price;
        if($calcTotal) {
            $this->total->count += $count;
            $this->total->price += $price;
        }
    }
}

class SummaryRow extends StdObject
{
    // ID
    public $id;
    // 項目名
    public $itemName;
    // 件数
    public $count;
    // 金額
    public $price;
}
