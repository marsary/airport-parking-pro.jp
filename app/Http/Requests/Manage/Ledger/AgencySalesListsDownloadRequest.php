<?php

namespace App\Http\Requests\Manage\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class AgencySalesListsDownloadRequest extends FormRequest
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
            'load_date_start_year'  => 'required|integer|digits:4',
            'load_date_start_month' => 'required|integer|between:1,12',
            'load_date_end_year'    => 'required|integer|digits:4',
            'load_date_end_month'   => 'required|integer|between:1,12',

            'mile' => 'nullable|in:1',
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
            'load_date_start_year'  => '入庫開始年',
            'load_date_start_month' => '入庫開始月',
            'load_date_end_year'    => '入庫終了年',
            'load_date_end_month'   => '入庫終了月',
            'mile' => 'マイル順にソート',
        ];
    }
}
