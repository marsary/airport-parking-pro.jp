<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class ArrivalFlightSearchRequest extends FormRequest
{
    /** 並べ替えに使える列（画面の列名 => 見出し） */
    public const SORTS = ['airport', 'terminal', 'name', 'arrive_date', 'arrive_time', 'dep', 'airline', 'codeshare'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'airport_id' => 'nullable|integer',
            'terminal' => 'nullable|integer|between:1,9',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'carrier' => 'nullable|string|max:3',
            'flight_no' => 'nullable|string|max:10',
            'hours' => 'nullable|integer|between:0,23',
            'minutes' => 'nullable|integer|between:0,59',
            'unset_only' => 'nullable|boolean',
            'sort' => 'nullable|in:' . implode(',', self::SORTS),
            'dir' => 'nullable|in:asc,desc',
            'limit' => 'nullable|integer|in:25,50,75,100,150',
        ];
    }

    public function attributes()
    {
        return [
            'date_from' => '到着日（から）',
            'date_to' => '到着日（まで）',
            'carrier' => '航空会社コード',
            'flight_no' => '便名',
        ];
    }

    public function messages()
    {
        return [
            'date_to.after_or_equal' => '到着日（まで）は到着日（から）以降の日付にしてください。',
        ];
    }
}
