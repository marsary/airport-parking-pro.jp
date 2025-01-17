<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class GoodRequest extends FormRequest
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
        $this->recordKey = "record_" . $this->route()->parameter('good', 0);
        return [
            "{$this->recordKey}.good_category_id" => 'required|int',
            "{$this->recordKey}.name" => 'required|string|max:255',
            "{$this->recordKey}.abbreviation" => 'nullable|string|max:255',
            "{$this->recordKey}.price" => 'required|int',
            "{$this->recordKey}.tax_type" => 'required|int',
            "{$this->recordKey}.memo" => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            "{$this->recordKey}.good_category_id" => '商品カテゴリーマスタID',
            "{$this->recordKey}.office_id" => '事業所ID',
            "{$this->recordKey}.status" => 'ステータス',
            "{$this->recordKey}.name" => '名称',
            "{$this->recordKey}.abbreviation" => '略称',
            "{$this->recordKey}.price" => '価格',
            "{$this->recordKey}.tax_type" => '税種別',
            "{$this->recordKey}.memo"             => 'メモ',
        ];
    }
}
