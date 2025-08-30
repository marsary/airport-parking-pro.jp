<?php

namespace App\Http\Requests\Manage\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class AgencyRecordsDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agency_code' => 'nullable|string|exists:agencies,code|max:100',
            'margin_year'  => 'required|integer|digits:4',
            'margin_month' => 'required|digits_between:1,2',
            'agency_sales_lists' => 'nullable',
            'agency_records' => 'nullable',
        ];
    }

    // protected function prepareForValidation()
    // {
    // }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'agency_code' => '代理店番号',
            'margin_year'  => '対象年',
            'margin_month' => '対象月',
        ];
    }
}
