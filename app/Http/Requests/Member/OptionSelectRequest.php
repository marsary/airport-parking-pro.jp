<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class OptionSelectRequest extends FormRequest
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
            'good_ids' => 'nullable|array',
            'good_ids.*' => 'int',
            'modal_good_ids' => 'nullable|array',
            'modal_good_ids.*' => 'int',
            'coupon_ids' => 'nullable|array',
            'coupon_ids.*' => 'int',
            // 'coupon_code' => 'nullable|max:255'
        ];
    }

    protected function prepareForValidation()
    {
        $good_ids = [];
        if(!empty($this->good_ids)) {
            $good_ids = explode(',', $this->good_ids);
        }
        $this->merge([
            'good_ids' => $good_ids,
        ]);
        $coupon_ids = [];
        if(!empty($this->coupon_ids)) {
            $coupon_ids = explode(',', $this->coupon_ids);
        }
        $this->merge([
            'coupon_ids' => $coupon_ids,
        ]);
    }

    public function attributes()
    {
        return [
            'good_ids' => 'オプション選択',
            'coupon_ids' => 'クーポン選択',
            // 'coupon_code' => 'クーポンコード',
        ];
    }
}
