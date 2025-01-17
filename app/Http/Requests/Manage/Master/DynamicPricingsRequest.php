<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class DynamicPricingsRequest extends FormRequest
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
        $this->recordKey = "record_" . $this->route()->parameter('agency_price', 0);
        $rules = [
            'sort' => 'required|int',
            "{$this->recordKey}.name" => 'required|string|max:255',
            "{$this->recordKey}.p10" => 'nullable|int',
            "{$this->recordKey}.p20" => 'nullable|int',
            "{$this->recordKey}.p30" => 'nullable|int',
            "{$this->recordKey}.p40" => 'nullable|int',
            "{$this->recordKey}.p50" => 'nullable|int',
            "{$this->recordKey}.p60" => 'nullable|int',
            "{$this->recordKey}.p70" => 'nullable|int',
            "{$this->recordKey}.p80" => 'nullable|int',
            "{$this->recordKey}.p90" => 'nullable|int',
            "{$this->recordKey}.p100" => 'nullable|int',
            "{$this->recordKey}.p110" => 'nullable|int',
            "{$this->recordKey}.p120" => 'nullable|int',
            "{$this->recordKey}.p130" => 'nullable|int',
            "{$this->recordKey}.p131" => 'nullable|int',
        ];

        return $rules;
    }


    public function attributes()
    {
        return [
            "{$this->recordKey}.name" => '名前',
            "{$this->recordKey}.p10" => '0-10%',
            "{$this->recordKey}.p20" => '11-20%',
            "{$this->recordKey}.p30" => '21-30%',
            "{$this->recordKey}.p40" => '31-40%',
            "{$this->recordKey}.p50" => '41-50%',
            "{$this->recordKey}.p60" => '51-60%',
            "{$this->recordKey}.p70" => '61-70%',
            "{$this->recordKey}.p80" => '71-80%',
            "{$this->recordKey}.p90" => '81-90%',
            "{$this->recordKey}.p100" => '91-100%',
            "{$this->recordKey}.p110" => '101-110%',
            "{$this->recordKey}.p120" => '111-120%',
            "{$this->recordKey}.p130" => '121-130%',
            "{$this->recordKey}.p131" => '131%-',
        ];
    }
}
