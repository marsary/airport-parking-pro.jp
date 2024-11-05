<?php

namespace App\Http\Requests\Manage\Master;

use App\Rules\CombinationFlagRule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'discount_amount' => 'required|int',
            'discount_type' => 'required|int',
            'good_category_id' => 'nullable|int',
            'limit_flg' => 'required|boolean',
            'combination_flg' => ['required','boolean', new CombinationFlagRule],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'memo' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled('start_date') && $this->filled('start_time')) {
            $startDate = $this->input('start_date');
            $startTime = $this->input('start_time');

            $this->merge([
                'start_date' => mergeDateAndTime($startDate, $startTime),
            ]);
        }
        if ($this->filled('end_date') && $this->filled('end_time')) {
            $endDate = $this->input('end_date');
            $endTime = $this->input('end_time');

            $this->merge([
                'end_date' => mergeDateAndTime($endDate, $endTime),
            ]);
        }
    }


    public function attributes()
    {
        return [
            'name' => '名前',
            'code' => 'コード',
            'discount_amount' => '割引値',
            'discount_type' => '割引種別',
            'good_category_id' => '対象カテゴリー',
            'limit_flg' => '利用回数制限',
            'combination_flg' => '併用可否',
            'start_date' => '割引対象となる入庫日',
            'end_date' => '終了日',
            'memo' => 'メモ',
        ];
    }
}
