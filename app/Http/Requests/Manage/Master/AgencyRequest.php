<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class AgencyRequest extends FormRequest
{
    private $recordKey;
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
        // 動的なレコードIDに基づいた入力データの取得
        $this->recordKey = "record_" . $this->route()->parameter('agency', 0);
        return [
            "{$this->recordKey}.name" => 'required|string|max:100',
            "{$this->recordKey}.code" => 'nullable|string|max:100',
            "{$this->recordKey}.zip" => 'nullable|string|max:8',
            "{$this->recordKey}.address1" => 'nullable|string|max:100',
            "{$this->recordKey}.address2" => 'nullable|string|max:100',
            "{$this->recordKey}.tel" => 'nullable|string|max:16',
            "{$this->recordKey}.keyword" => 'nullable|string',
            "{$this->recordKey}.branch" => 'nullable|string|max:100',
            "{$this->recordKey}.department" => 'nullable|string|max:20',
            "{$this->recordKey}.position" => 'nullable|string|max:20',
            "{$this->recordKey}.person" => 'nullable|string|max:50',
            "{$this->recordKey}.email" => 'nullable|email|max:255',
            "{$this->recordKey}.payment_site" => 'nullable|string|max:100',
            "{$this->recordKey}.payment_destination" => 'nullable|string|max:100',
            "{$this->recordKey}.memo" => 'nullable|string',
            "{$this->recordKey}.monthly_fixed_cost_flag" => 'nullable|boolean',
            "{$this->recordKey}.monthly_fixed_cost" => 'nullable|int',
            "{$this->recordKey}.incentive_flag" => 'nullable|boolean',
            "{$this->recordKey}.incentive" => 'nullable|int',
            "{$this->recordKey}.banner_comment_set" => 'nullable|string|max:255',
            "{$this->recordKey}.title_set" => 'nullable|string|max:255',
            "{$this->recordKey}.logo_image" => 'nullable|image',
            "{$this->recordKey}.campaign_image" => 'nullable|image',
        ];
    }

    public function attributes()
    {
        return [
            "{$this->recordKey}.name" => '名前',
            "{$this->recordKey}.code" => 'コード',
            "{$this->recordKey}.zip" => '郵便番号',
            "{$this->recordKey}.address1" => '住所１',
            "{$this->recordKey}.address2" => '住所２',
            "{$this->recordKey}.tel" => '電話番号',
            "{$this->recordKey}.keyword" => 'キーワード',
            "{$this->recordKey}.branch" => '支店名',
            "{$this->recordKey}.department" => '担当者部署',
            "{$this->recordKey}.position" => '担当者役職',
            "{$this->recordKey}.person" => '担当者氏名',
            "{$this->recordKey}.email" => '担当者メールアドレス',
            "{$this->recordKey}.payment_site" => '支払サイト',
            "{$this->recordKey}.payment_destination" => '振込先情報',
            "{$this->recordKey}.memo" => '社内共有メモ',
            "{$this->recordKey}.monthly_fixed_cost_flag" => '月額固定費用フラグ',
            "{$this->recordKey}.monthly_fixed_cost" => '月額固定費用',
            "{$this->recordKey}.incentive_flag" => 'インセンティブフラグ',
            "{$this->recordKey}.incentive" => 'インセンティブ',
            "{$this->recordKey}.banner_comment_set" => 'バナーコメントの設定',
            "{$this->recordKey}.title_set" => 'タイトルの設定',
            "{$this->recordKey}.logo_image" => 'ロゴ画像',
            "{$this->recordKey}.campaign_image" => 'キャンペーン画像',
        ];
    }
}
