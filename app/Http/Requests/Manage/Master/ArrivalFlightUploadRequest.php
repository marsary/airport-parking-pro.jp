<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class ArrivalFlightUploadRequest extends FormRequest
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
            'csvFileInput' => 'required|file|mimes:txt,csv|max:20480', // 20MB まで
        ];
    }

    public function attributes()
    {
        return [
            'csvFileInput' => 'ファイル',
        ];
    }

    public function messages()
    {
        return [
            'csvFileInput.mimes' => 'ファイルはCSV形式でなくてはなりません。',
            'csvFileInput.max' => 'ファイルは20MB以下にしてください。',
        ];
    }
}
