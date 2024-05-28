<?php

namespace App\Http\Requests\Member;

use App\Rules\FlightNoDateRule;
use Illuminate\Foundation\Http\FormRequest;

class EntryCarRequest extends FormRequest
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
            'car_maker_id' => 'nullable',
            'car_id' => 'required',
            'car_color_id' => 'required',
            'car_number' => 'required|numeric|digits:4',
            'flight_no' => ['nullable', new FlightNoDateRule],
            'arrive_date' => 'nullable|date',
            'num_members' => 'nullable|numeric',
        ];
    }


    public function attributes()
    {
        return [
            'car_maker_id' => 'メーカー',
            'car_id' => '車種',
            'car_color_id' => '色',
            'car_number' => 'ナンバー',
            'flight_no' => '到着便',
            'arrive_date' => '到着日',
            'num_members' => 'ご利用人数',
        ];
    }
}
