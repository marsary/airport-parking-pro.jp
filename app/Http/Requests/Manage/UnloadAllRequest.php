<?php

namespace App\Http\Requests\Manage;

use App\Rules\FlightNoDateRule;
use App\Rules\PhoneRule;
use App\Rules\ZipcodeRule;
use Illuminate\Foundation\Http\FormRequest;

class UnloadAllRequest extends FormRequest
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
            'deal_id' => 'nullable|array',
            'deal_id.*' => 'int',
            // 'deal_ids' => 'nullable|array',
            // 'deal_ids.*' => 'int',
        ];
    }

    // protected function prepareForValidation()
    // {
    //     dd($this->deal_id);
    //     $deal_ids = [];
    //     if(!empty($this->deal_ids)) {
    //         $deal_ids = explode(',', $this->deal_ids);
    //     }
    //     $this->merge([
    //         'deal_ids' => $deal_ids,
    //     ]);
    // }

    public function attributes()
    {
        return [
            'deal_id' => '取引ID',
            // 'deal_ids' => '取引ID',
        ];
    }
}
