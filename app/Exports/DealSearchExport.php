<?php

namespace App\Exports;

use App\Exports\Commons\CustomNumberFormat;
use App\Models\Deal;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DealSearchExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithColumnWidths
{
    use Exportable;

    /** @var array<int> */
    protected $dealIds;

    private static $idx;

    public function __construct(array $dealIds)
    {
        $this->dealIds = $dealIds;
    }

    public function query()
    {
        return Deal::whereIn('id', $this->dealIds)->with(['member', 'agency'])
            ->orderBy('reserve_date', 'asc')
            ;
    }

    public function headings(): array
    {
        return [
            '予約コード',
            '予約日時',
            '予約経路',
            '受付コード',
            '入庫日時',
            '出庫予定日',
            '利用日数',
            '顧客コード',
            'お客様氏名',
            'ふりがな',
            '利用回数',
        ];
    }

    public function map($deal): array
    {
        self::$idx += 1;
        /** @var Deal $deal */

        return [
            // 予約コード
            $deal->reserve_code,
            // 予約日時
            $deal->reserve_date?->format('Y/m/d H:i'),
            // 予約経路
            $deal->agency?->name,
            // 受付コード
            $deal->receipt_code,
            // 入庫日時
            $deal->loadDateTime(),
            // 出庫予定日
            $deal->unload_date_plan?->format("Y/m/d"),
            // 利用日数
            $deal->num_days,
            // 顧客コード
            $deal->member?->member_code,
            // お客様氏名
            $deal->name,
            // ふりがな
            $deal->kana,
            // 利用回数
            $deal->member?->used_num,
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 16,
            'C' => 16,
            'D' => 12,
            'E' => 16,
            'F' => 11,
            'G' => 10,
            'H' => 12,
            'I' => 16,
            'J' => 16,
            'K' => 10,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => CustomNumberFormat::FORMAT_DATE_TIME_SLASH,
            'E' => CustomNumberFormat::FORMAT_DATE_TIME_SLASH,
            'F' => CustomNumberFormat::FORMAT_DATE_SLASH,
        ];
    }


    public function styles(Worksheet $sheet)
    {
    }
}
