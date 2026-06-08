<?php

namespace App\Http\Requests\Form;

use App\Rules\PhoneRule;
use App\Rules\ZipcodeRule;
use Illuminate\Foundation\Http\FormRequest;

class EntryInfoRequest extends FormRequest
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
            'name'	=> 'nullable',
            'kana'	=> 'required',
            'tel'	=> ['required','max:15', new PhoneRule],
            'zip'	=> ['nullable','max:8', new ZipcodeRule],
            'email'	=> 'required|email',
            'receipt_address'	=> 'nullable|max:100',
            'remarks'	=> 'nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'kana.required' => 'ふりがなが設定されていません。',
            'tel.required'  => '電話番号が設定されていません。',
            'email.required'  => 'メールアドレスが設定されていません。',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '氏名',
            'kana' => 'ふりがな',
            'tel' => '携帯番号',
            'zip' => '郵便番号',
            'email' => 'メールアドレス',
            'receipt_address' => '領収書の宛名',
            'remarks' => '備考',
        ];
    }
}
