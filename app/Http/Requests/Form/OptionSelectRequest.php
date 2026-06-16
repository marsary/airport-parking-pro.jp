<?php

namespace App\Http\Requests\Form;

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
            'insurance' => 'required|in:1,0',
            'carwash' => 'required|in:1,0',
        ];
    }

    public function attributes()
    {
        return [
            'insurance' => '旅行保険への加入',
            'carwash' => '洗車希望',
        ];
    }
}
