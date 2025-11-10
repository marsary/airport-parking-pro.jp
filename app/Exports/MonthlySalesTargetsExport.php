<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlySalesTargetsExport implements FromArray, WithHeadings
{
    use Exportable;

    /** @var array<array<string, mixed>> */
    protected $csvData;
    /** @var int */
    private int $year;

    public function __construct(array $csvData)
    {
        // 年は必須データなので、先に取得してデフォルト値を設定する
        $this->year = $csvData['year'] ?? Carbon::today()->year;
        // csvDataからyear要素を削除し、テーブルデータのみにする
        unset($csvData['year']);
        $this->csvData = $csvData;
    }

    /**
     * CSV出力するデータ
     */
    public function array(): array
    {
        return $this->csvData;
    }

    public function map($row): array
    {
        return [
            // '事業所名',
            $row['事業所名'],
            // '対象年月',
            $row['対象年月'],
            // '総売上目標',
            $row['総売上目標'],
            // '駐車料金売上目標',
            $row['駐車料金売上目標'],
            // '商品カテゴリー1名称',
            $row['商品カテゴリー1名称'],
            // '商品カテゴリー1売上目標',
            $row['商品カテゴリー1売上目標'],
            // '商品カテゴリー2名称',
            $row['商品カテゴリー2名称'],
            // '商品カテゴリー2売上目標',
            $row['商品カテゴリー2売上目標'],
            // '商品カテゴリー3名称',
            $row['商品カテゴリー3名称'],
            // '商品カテゴリー3売上目標',
            $row['商品カテゴリー3売上目標'],
            // '商品カテゴリー4名称',
            $row['商品カテゴリー4名称'],
            // '商品カテゴリー4売上目標',
            $row['商品カテゴリー4売上目標'],
        ];
    }

    public function headings(): array
    {
        return [
            '事業所名',
            '対象年月',
            '総売上目標',
            '駐車料金売上目標',
            '商品カテゴリー1名称',
            '商品カテゴリー1売上目標',
            '商品カテゴリー2名称',
            '商品カテゴリー2売上目標',
            '商品カテゴリー3名称',
            '商品カテゴリー3売上目標',
            '商品カテゴリー4名称',
            '商品カテゴリー4売上目標',
        ];
    }

    // public function generator(): \Generator
    // {
    //     $tableTitles = [
    //         'total_sales' => '月間総売上',
    //         'parking_fee' => '駐車料金',
    //         'good_category_1' => '商品カテゴリー1 月間総売上',
    //         'good_category_2' => '商品カテゴリー2 月間総売上',
    //         'good_category_3' => '商品カテゴリー3 月間総売上',
    //         'good_category_4' => '商品カテゴリー4 月間総売上',
    //     ];

    //     $rowTitles = [
    //         'target' => '売上目標',
    //         'result' => '売上実績',
    //         'difference' => '目標と実績の差額',
    //         'achievement_rate' => '売上目標に対する達成率',
    //     ];

    //     $isFirstTable = true;

    //     foreach ($tableTitles as $tableKey => $tableTitle) {
    //         if (!isset($this->tableData[$tableKey])) {
    //             continue;
    //         }

    //         if (!$isFirstTable) {
    //             // テーブル間に一行開ける
    //             yield [''];
    //         }
    //         $isFirstTable = false;

    //         // 1. テーブル見出し行
    //         $headerRow = [$tableTitle];
    //         for ($month = 1; $month <= 12; $month++) {
    //             $headerRow[] = "{$this->year}年{$month}月";
    //         }
    //         yield $headerRow;

    //         // 2. データ行
    //         foreach ($rowTitles as $rowKey => $rowTitle) {
    //             $dataRow = [$rowTitle];
    //             for ($month = 1; $month <= 12; $month++) {
    //                 $value = $this->tableData[$tableKey][$rowKey][$month] ?? '';
    //                 if ($rowKey === 'achievement_rate' && is_numeric($value)) {
    //                     $dataRow[] = $value . '%';
    //                 } else {
    //                     $dataRow[] = $value;
    //                 }
    //             }
    //             yield $dataRow;
    //         }
    //     }
    // }
}
