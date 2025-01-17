<?php

namespace App\Exports;

use App\Exports\Commons\CustomNumberFormat;
use App\Models\Agency;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class AgencyExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithColumnWidths, WithCustomCsvSettings
{
    use Exportable;

    /** @var array<int> */
    protected $agencyIds;

    private static $idx;

    public function __construct(array $agencyIds)
    {
        $this->agencyIds = $agencyIds;
    }

    public function getCsvSettings(): array
    {
         return [
            'output_encoding' => 'SJIS',
        ];

    }

    public function query()
    {
        return Agency::whereIn('id', $this->agencyIds)->orderBy('name', 'asc')
            ;
    }

    public function headings(): array
    {
        return [
            'ID',
            '社名',
            '支店名',
            '住所',
            '電話番号',
            '担当者部署',
            '担当者役職',
            '担当者氏名',
            '担当者メールアドレス',
        ];
    }

    public function map($agency): array
    {
        self::$idx += 1;
        /** @var Agency $agency */

        return [
            // ID
            $agency->id,
            // 社名
            $agency->name,
            // 支店名
            $agency->branch,
            // 住所
            $agency->address,
            // 電話番号
            $agency->tel,
            // 担当者部署
            $agency->department,
            // 担当者役職
            $agency->position,
            // 担当者氏名
            $agency->person,
            // 担当者メールアドレス
            $agency->email,
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 16,
            'C' => 16,
            'D' => 16,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 12,
            'I' => 12,
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'B' => CustomNumberFormat::FORMAT_DATE_TIME_SLASH,
        ];
    }


    public function styles(Worksheet $sheet)
    {
    }
}
