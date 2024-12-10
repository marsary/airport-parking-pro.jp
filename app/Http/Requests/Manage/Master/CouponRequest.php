<?php

namespace App\Http\Requests\Manage\Master;

use App\Rules\CombinationFlagRule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $this->recordKey = "record_" . $this->route()->parameter('coupon', 0);
        return [
            "{$this->recordKey}.name" => 'required|string|max:255',
            "{$this->recordKey}.code" => 'nullable|string|max:255',
            "{$this->recordKey}.discount_amount" => 'required|int',
            "{$this->recordKey}.discount_type" => 'required|int',
            "{$this->recordKey}.good_category_id" => 'nullable|int',
            "{$this->recordKey}.limit_flg" => 'required|boolean',
            "{$this->recordKey}.combination_flg" => ['required','boolean', new CombinationFlagRule],
            "{$this->recordKey}.start_date" => 'required|date',
            "{$this->recordKey}.end_date" => 'required|date|after:start_date',
            "{$this->recordKey}.memo" => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled("{$this->recordKey}.start_date") && $this->filled("{$this->recordKey}.start_time")) {
            $startDate = $this->input("{$this->recordKey}.start_date");
            $startTime = $this->input("{$this->recordKey}.start_time");

            $this->merge([
                "{$this->recordKey}.start_date" => mergeDateAndTime($startDate, $startTime),
            ]);
        }
        if ($this->filled('end_date') && $this->filled('end_time')) {
            $endDate = $this->input("{$this->recordKey}.end_date");
            $endTime = $this->input("{$this->recordKey}.end_time");

            $this->merge([
                "{$this->recordKey}.end_date" => mergeDateAndTime($endDate, $endTime),
            ]);
        }
    }


    public function attributes()
    {
        return [
            "{$this->recordKey}.name" => '名前',
            "{$this->recordKey}.code" => 'コード',
            "{$this->recordKey}.discount_amount" => '割引値',
            "{$this->recordKey}.discount_type" => '割引種別',
            "{$this->recordKey}.good_category_id" => '対象カテゴリー',
            "{$this->recordKey}.limit_flg" => '利用回数制限',
            "{$this->recordKey}.combination_flg" => '併用可否',
            "{$this->recordKey}.start_date" => '割引対象となる入庫日',
            "{$this->recordKey}.end_date" => '終了日',
            "{$this->recordKey}.memo" => 'メモ',
        ];
    }
}
