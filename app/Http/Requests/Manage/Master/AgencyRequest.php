<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class AgencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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

    public function attributes()
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
}
