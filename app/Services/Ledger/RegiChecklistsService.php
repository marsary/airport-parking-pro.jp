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


        return $officeTables;
    }

    /**
     * 売上 合計
     *
     * @return TotalSales
     */
    public function getTotalSalesTableData()
    {
    }

}


