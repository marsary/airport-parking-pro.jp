<?php

namespace App\Imports;

use App\Models\Agency;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AgencyImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, WithCustomCsvSettings
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Agency([
            'office_id' => config('const.commons.office_id'),
            'name' => $row['name'],
            'code' => isset($row['code']) ? $row['code']:null,
            'zip' => isset($row['zip']) ? $row['zip']:null,
            'address1' => isset($row['address1']) ? $row['address1']:null,
            'address2' => isset($row['address2']) ? $row['address2']:null,
            'tel' => isset($row['tel']) ? $row['tel']:null,
            'keyword' => isset($row['keyword']) ? $row['keyword']:null,
            'branch' => isset($row['branch']) ? $row['branch']:null,
            'department' => isset($row['department']) ? $row['department']:null,
            'position' => isset($row['position']) ? $row['position']:null,
            'person' => isset($row['person']) ? $row['person']:null,
            'email' => isset($row['email']) ? $row['email']:null,
            'payment_site' => isset($row['payment_site']) ? $row['payment_site']:null,
            'payment_destination' => isset($row['payment_destination']) ? $row['payment_destination']:null,
            'memo' => isset($row['memo']) ? $row['memo']:null,
            'monthly_fixed_cost_flag' => isset($row['monthly_fixed_cost_flag']) ? $row['monthly_fixed_cost_flag']:null,
            'monthly_fixed_cost' => isset($row['monthly_fixed_cost']) ? $row['monthly_fixed_cost']:null,
            'incentive_flag' => isset($row['incentive_flag']) ? $row['incentive_flag']:null,
            'incentive' => isset($row['incentive']) ? $row['incentive']:null,
            'banner_comment_set' => isset($row['banner_comment_set']) ? $row['banner_comment_set']:null,
            'title_set' => isset($row['title_set']) ? $row['title_set']:null,
        ]);
    }

    public function getCsvSettings(): array
    {
         return [
            'input_encoding' => 'SJIS',
        ];

    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:8',
            'address1' => 'nullable|string|max:100',
            'address2' => 'nullable|string|max:100',
            'tel' => 'nullable|string|max:16',
            'keyword' => 'nullable|string',
            'branch' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:20',
            'person' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'payment_site' => 'nullable|string|max:100',
            'payment_destination' => 'nullable|string|max:100',
            'memo' => 'nullable|string',
            'monthly_fixed_cost_flag' => 'nullable|boolean',
            'monthly_fixed_cost' => 'nullable|int',
            'incentive_flag' => 'nullable|boolean',
            'incentive' => 'nullable|int',
            'banner_comment_set' => 'nullable|string|max:255',
            'title_set' => 'nullable|string|max:255',
            'logo_image' => 'nullable|image',
            'campaign_image' => 'nullable|image',
        ];
    }

    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            'name' => '名前',
            'code' => 'コード',
            'zip' => '郵便番号',
            'address1' => '住所１',
            'address2' => '住所２',
            'tel' => '電話番号',
            'keyword' => 'キーワード',
            'branch' => '支店名',
            'department' => '担当者部署',
            'position' => '担当者役職',
            'person' => '担当者氏名',
            'email' => '担当者メールアドレス',
            'payment_site' => '支払サイト',
            'payment_destination' => '振込先情報',
            'memo' => '社内共有メモ',
            'monthly_fixed_cost_flag' => '月額固定費用フラグ',
            'monthly_fixed_cost' => '月額固定費用',
            'incentive_flag' => 'インセンティブフラグ',
            'incentive' => 'インセンティブ',
            'banner_comment_set' => 'バナーコメントの設定',
            'title_set' => 'タイトルの設定',
            'logo_image' => 'ロゴ画像',
            'campaign_image' => 'キャンペーン画像',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
