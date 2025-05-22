<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class LoadUnloadFullLimitSettingDestroyByDateRequest extends FormRequest
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
            'delete_target_date' => 'required|date',
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
            'delete_target_date.required' => '対象日は必須です。',
            'delete_target_date.date'     => '対象日は有効な日付形式で入力してください。',
        ];
    }

    protected function prepareForValidation()
    {
        $activeCalendarYear = $this->input('delete_active_calendar_year', \Carbon\Carbon::today()->year);
        $activeCalendarMonth1 = $this->input('delete_active_calendar_month1', \Carbon\Carbon::today()->month);

        session()->put('active_calendar_year', $activeCalendarYear);
        session()->put('active_calendar_month1', $activeCalendarMonth1);
    }
}
