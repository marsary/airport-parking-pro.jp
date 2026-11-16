<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class LegacyCompareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $file = 'required|file|mimes:txt,csv|max:20480';
        return [
            'corp' => $file,
            'dpt' => $file,
            'term' => $file,
            'flt' => $file,
        ];
    }

    public function attributes()
    {
        return [
            'corp' => '航空会社（flt_corp_tbl）',
            'dpt' => '出発空港（flt_dpt_tbl）',
            'term' => 'ターミナル（flt_corp_term_tbl）',
            'flt' => '到着便（flt_tbl）',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => ':attribute のCSVを選んでください。',
            '*.mimes' => ':attribute はCSV形式のファイルを選んでください。',
            '*.max' => ':attribute は20MB以下にしてください。',
        ];
    }
}
