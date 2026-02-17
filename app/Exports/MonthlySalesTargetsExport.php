<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonthlySalesTargetsExport implements FromArray, WithHeadings, WithMapping, WithCustomCsvSettings
{
    use Exportable;

    /** @var array<array<string, mixed>> */
    protected $csvData;

    private int $year;

    public function __construct(array $csvData)
    {
        // 年は必須データなので、先に取得してデフォルト値を設定する
        $this->year = $csvData['year'] ?? Carbon::today()->year;
        // csvDataからyear要素を削除し、テーブルデータのみにする
        unset($csvData['year']);
        $this->csvData = $csvData;
    }

    public function getCsvSettings(): array
    {
         return [
            'output_encoding' => 'SJIS',
        ];
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
}
