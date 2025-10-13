<?php

namespace App\Http\Requests\Manage\Master\SeasonPriceSetting;

use Illuminate\Foundation\Http\FormRequest;

class SeasonPriceSettingUpdateByDateRequest extends FormRequest
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
            'edit_season_price' => 'nullable|integer|min:0',
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
            'edit_season_price' => 'シーズン料金',
        ];
    }
}
