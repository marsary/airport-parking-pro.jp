<?php

namespace App\Services\Ledger\Repositories;

use Illuminate\Http\Request;

interface PaymentSummaryRepositoryInterface
{
    public function getLoadUnloadCounts(Request $request);

    public function getPaymentMethodTableData(Request $request);

    public function getPaymentGoodsData(Request $request);

    public function getCreditTableOfficeData(Request $request);

}
