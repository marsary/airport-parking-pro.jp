<?php

namespace App\Http\Requests\Manage\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class RegiPaymentSummariesRequest extends FormRequest
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
            'entry_date_start' => 'nullable|date',
            'entry_date_fin' => 'nullable|date|after:entry_date_start',
        ];
    }



    public function attributes()
    {
        return [
            'entry_date_start' => '開始日',
            'entry_date_fin' => '終了日',
        ];
    }
}
