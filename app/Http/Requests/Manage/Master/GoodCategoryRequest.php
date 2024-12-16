<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class GoodCategoryRequest extends FormRequest
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
        $this->recordKey = "record_" . $this->route()->parameter('good_category', 0);
        return [
            "{$this->recordKey}.name" => 'string|max:100',
            "{$this->recordKey}.type" => 'int',
            "{$this->recordKey}.memo" => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            "{$this->recordKey}.name" => '名前',
            "{$this->recordKey}.type" => '区分',
            "{$this->recordKey}.memo" => 'メモ',
        ];
    }
}
