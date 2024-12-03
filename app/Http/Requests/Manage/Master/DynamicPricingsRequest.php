<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class DynamicPricingsRequest extends FormRequest
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
            'p10' => 'nullable|int',
            'p20' => 'nullable|int',
            'p30' => 'nullable|int',
            'p40' => 'nullable|int',
            'p50' => 'nullable|int',
            'p60' => 'nullable|int',
            'p70' => 'nullable|int',
            'p80' => 'nullable|int',
            'p90' => 'nullable|int',
            'p100' => 'nullable|int',
            'p110' => 'nullable|int',
            'p120' => 'nullable|int',
            'p130' => 'nullable|int',
            'p131' => 'nullable|int',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名前',
            'p10' => '0-10%',
            'p20' => '11-20%',
            'p30' => '21-30%',
            'p40' => '31-40%',
            'p50' => '41-50%',
            'p60' => '51-60%',
            'p70' => '61-70%',
            'p80' => '71-80%',
            'p90' => '81-90%',
            'p100' => '91-100%',
            'p110' => '101-110%',
            'p120' => '111-120%',
            'p130' => '121-130%',
            'p131' => '131%-',
        ];
    }
}
