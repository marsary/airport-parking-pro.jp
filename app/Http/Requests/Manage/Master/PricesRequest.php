<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class PricesRequest extends FormRequest
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
        $this->recordKey = "record_" . $this->route()->parameter('price', 0);
        return [
            "{$this->recordKey}.start_date" => 'required|date',
            "{$this->recordKey}.end_date" => "required|date|after:{$this->recordKey}.start_date",
            "{$this->recordKey}.base_price" => 'nullable|int',
            "{$this->recordKey}.d1" => 'required|int',
            "{$this->recordKey}.d2" => 'required|int',
            "{$this->recordKey}.d3" => 'required|int',
            "{$this->recordKey}.d4" => 'required|int',
            "{$this->recordKey}.d5" => 'required|int',
            "{$this->recordKey}.d6" => 'required|int',
            "{$this->recordKey}.d7" => 'required|int',
            "{$this->recordKey}.d8" => 'required|int',
            "{$this->recordKey}.d9" => 'required|int',
            "{$this->recordKey}.d10" => 'required|int',
            "{$this->recordKey}.d11" => 'required|int',
            "{$this->recordKey}.d12" => 'required|int',
            "{$this->recordKey}.d13" => 'required|int',
            "{$this->recordKey}.d14" => 'required|int',
            "{$this->recordKey}.d15" => 'required|int',
            "{$this->recordKey}.price_per_day" => 'required|int',
            "{$this->recordKey}.late_fee" => 'nullable|int',
            "{$this->recordKey}.memo" => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            "{$this->recordKey}.start_date" => 'サービス開始日',
            "{$this->recordKey}.end_date" => 'サービス終了日',
            "{$this->recordKey}.base_price" => 'ベース料金',
            "{$this->recordKey}.d1" => '1日',
            "{$this->recordKey}.d2" => '2日',
            "{$this->recordKey}.d3" => '3日',
            "{$this->recordKey}.d4" => '4日',
            "{$this->recordKey}.d5" => '5日',
            "{$this->recordKey}.d6" => '6日',
            "{$this->recordKey}.d7" => '7日',
            "{$this->recordKey}.d8" => '8日',
            "{$this->recordKey}.d9" => '9日',
            "{$this->recordKey}.d10" => '10日',
            "{$this->recordKey}.d11" => '11日',
            "{$this->recordKey}.d12" => '12日',
            "{$this->recordKey}.d13" => '13日',
            "{$this->recordKey}.d14" => '14日',
            "{$this->recordKey}.d15" => '15日',
            "{$this->recordKey}.price_per_day" => '1日ごとの料金',
            "{$this->recordKey}.late_fee" => '延滞料',
            "{$this->recordKey}.memo" => 'メモ',
        ];
    }
}
