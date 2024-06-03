<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class DealUpdateMemoRequest extends FormRequest
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
            'member_memo' => 'nullable',
            'reserve_memo' => 'nullable',
            'reception_memo' => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'member_memo' => '顧客メモ',
            'reserve_memo' => '予約メモ',
            'reception_memo' => '受付メモ',
        ];
    }
}
