<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class GoodRequest extends FormRequest
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
            'good_category_id' => 'required|int',
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'price' => 'required|int',
            'tax_type' => 'required|int',
            'memo' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'good_category_id' => '商品カテゴリーマスタID',
            'office_id' => '事業所ID',
            'status' => 'ステータス',
            'name' => '名称',
            'abbreviation' => '略称',
            'price' => '価格',
            'tax_type' => '税種別',
            'memo'             => 'メモ',
        ];
    }
}
