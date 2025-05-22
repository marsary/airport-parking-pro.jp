<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class LoadUnloadFullLimitSettingStoreAllRequest extends FormRequest
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
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'load_limit' => 'nullable|integer|min:0',
            'unload_limit' => 'nullable|integer|min:0',
            'at_closing_time' => 'nullable|integer|min:0',
            'per_fifteen_munites' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        $activeCalendarYear = $this->input('active_calendar_year', \Carbon\Carbon::today()->year);
        $activeCalendarMonth1 = $this->input('active_calendar_month1', \Carbon\Carbon::today()->month);

        session()->put('active_calendar_year', $activeCalendarYear);
        session()->put('active_calendar_month1', $activeCalendarMonth1);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'start_date' => '開始日',
            'end_date' => '終了日',
            'load_limit' => '入庫上限',
            'unload_limit' => '出庫上限',
            'at_closing_time' => 'おわり在庫',
            'per_fifteen_munites' => '15分あたりの上限',
        ];
    }
}
