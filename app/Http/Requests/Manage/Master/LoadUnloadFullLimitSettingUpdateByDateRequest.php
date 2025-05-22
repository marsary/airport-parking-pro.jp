<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class LoadUnloadFullLimitSettingUpdateByDateRequest extends FormRequest
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
            'edit_target_date' => 'required|date',
            'edit_load_limit' => 'nullable|integer|min:0',
            'edit_unload_limit' => 'nullable|integer|min:0',
            'edit_at_closing_time' => 'nullable|integer|min:0',
            'edit_per_fifteen_munites' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        $activeCalendarYear = $this->input('edit_active_calendar_year', \Carbon\Carbon::today()->year);
        $activeCalendarMonth1 = $this->input('edit_active_calendar_month1', \Carbon\Carbon::today()->month);

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
            'edit_target_date' => '対象日',
            'edit_load_limit' => '入庫上限',
            'edit_unload_limit' => '出庫上限',
            'edit_at_closing_time' => 'おわり在庫',
            'edit_per_fifteen_munites' => '15分あたりの上限',
        ];
    }
}
