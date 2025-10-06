<?php

namespace App\Http\Requests\Manage\Master\SeasonPriceSetting;

use Illuminate\Foundation\Http\FormRequest;

class SeasonPriceSettingStoreAllRequest extends FormRequest
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
            'season_price' => 'nullable|integer|min:0',
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
            'season_price' => 'シーズン料金',
        ];
    }
}
