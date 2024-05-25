<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class DealUpdateGoodsRequest extends FormRequest
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
            'dealGoods' => 'array',
            'total_price' => 'int',
            'total_tax' => 'int',
        ];
    }

    protected function prepareForValidation()
    {
        // if(empty($this->label_tag)) {
        //     return;
        // }
        // $this->merge([
        //     'label_tag' => array_filter($this->label_tag),
        // ]);
    }

    public function attributes()
    {
        return [
            'dealGoods' => '取引商品',
            'total_price' => '合計金額',
            'total_tax' => '合計税額',
        ];
    }
}
