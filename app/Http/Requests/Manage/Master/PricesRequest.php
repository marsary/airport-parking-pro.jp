<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class PricesRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'base_price' => 'nullable|int',
            'd1' => 'required|int',
            'd2' => 'required|int',
            'd3' => 'required|int',
            'd4' => 'required|int',
            'd5' => 'required|int',
            'd6' => 'required|int',
            'd7' => 'required|int',
            'd8' => 'required|int',
            'd9' => 'required|int',
            'd10' => 'required|int',
            'd11' => 'required|int',
            'd12' => 'required|int',
            'd13' => 'required|int',
            'd14' => 'required|int',
            'd15' => 'required|int',
            'price_per_day' => 'required|int',
            'late_fee' => 'nullable|int',
            'memo' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'start_date' => 'サービス開始日',
            'end_date' => 'サービス終了日',
            'base_price' => 'ベース料金',
            'd1' => '1日',
            'd2' => '2日',
            'd3' => '3日',
            'd4' => '4日',
            'd5' => '5日',
            'd6' => '6日',
            'd7' => '7日',
            'd8' => '8日',
            'd9' => '9日',
            'd10' => '10日',
            'd11' => '11日',
            'd12' => '12日',
            'd13' => '13日',
            'd14' => '14日',
            'd15' => '15日',
            'price_per_day' => '1日ごとの料金',
            'late_fee' => '延滞料',
            'memo' => 'メモ',
        ];
    }
}
