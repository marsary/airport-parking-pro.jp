<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;

class AgencySalesListsGenExport implements FromGenerator
{
    use Exportable;

    /** @var array<int,Collection> */
    protected $agencyRecords;

    private static $idx;

    public function __construct(array $agencyRecords)
    {
        $this->agencyRecords = $agencyRecords;
    }

    public function generator(): \Generator
    {
        foreach ($this->agencyRecords as $agencyId => $agencyRecords) {
            // ---- 代理店ヘッダー行 ----
            yield [
                $agencyId,
                $agencyRecords->first()?->agency_name,
                // 'サイクル3ヶ月',
            ];

            // ---- カラム見出し行 ----
            yield [
                '受付ID','顧客ID','顧客名','入庫日','出庫日','日数','出発地',
                '車番','車種','割引率','割引券名','割引券金額','駐車料金',
                'クーポン',
                // 'マージン率','マージン額', // 使用しない
                // 'マイル'
            ];

            // ---- データ行 ----
            foreach ($agencyRecords as $agencyRecord) {
                yield [
                    // 受付ID
                    $agencyRecord->receipt_code,
                    // 顧客ID
                    $agencyRecord->member_code,
                    // 顧客名
                    $agencyRecord->reserve_name,
                    // 入庫日
                    $agencyRecord->load_date->format('Y-m-d'),
                    // 出庫日
                    $agencyRecord->unload_date->format('Y-m-d'),
                    // 日数
                    $agencyRecord->num_days,
                    // 出発地
                    $agencyRecord->dep_airport_name,
                    // 車番
                    $agencyRecord->car_number,
                    // 車種
                    $agencyRecord->car_name,
                    // 割引率
                    $agencyRecord->deal->dsc_rate,
                    // 割引券名
                    $agencyRecord->coupon_name,
                    // 割引券金額
                    $agencyRecord->dt_price_load,
                    // 駐車料金
                    $agencyRecord->price,
                    // クーポン
                    isBlank($agencyRecord->coupon_name) ? '無' : '有',
                    // マージン率
                    // null,
                    // マージン額
                    // null,
                    // マイル
                    // null,
                ];
            }
        }
    }
}
