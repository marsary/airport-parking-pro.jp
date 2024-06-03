<?php

namespace App\Http\Requests\Manage;

use App\Enums\CarSize;
use App\Rules\PhoneRule;
use App\Rules\ZipcodeRule;
use Illuminate\Foundation\Http\FormRequest;

class DealSearchRequest extends FormRequest
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
            'reserved' => 'nullable|boolean',
            'load_today' => 'nullable|boolean',
            'loaded' => 'nullable|boolean',
            'unload_plan_today' => 'nullable|boolean',
            'pending' => 'nullable|boolean',
            'unloaded' => 'nullable|boolean',
            'reserve_code' => 'nullable',
            'receipt_code' => 'nullable',
            'reserve_date' => 'nullable|date',
            'agency_id' => 'nullable',
            'load_date_start' => 'nullable|date',
            'load_date_end' => 'nullable|date',
            'load_time_start' => 'nullable|date_format:H:i',
            'load_time_end' => 'nullable|date_format:H:i',
            'unload_date_plan_start' => 'nullable|date',
            'unload_date_plan_end' => 'nullable|date',
            'unload_date_start' => 'nullable|date',
            'unload_date_end' => 'nullable|date',
            'num_days_start' => 'nullable|int',
            'num_days_end' => 'nullable|int',
            'member_code' => 'nullable',
            'name' => 'nullable|max:255',
            'kana' => 'nullable|max:255',
            'user_num' => 'nullable|int',
            'label_tag' => 'nullable|array',
            'label_tag.*' => 'nullable',
            'zip' => ['nullable','max:8', new ZipcodeRule],
            'tel' => ['nullable','max:15', new PhoneRule],
            'email' => 'nullable|email',
            'line_id' => 'nullable',
            'arrive_date_start' => 'nullable|date',
            'arrive_date_end' => 'nullable|date',
            'arrive_time_start' => 'nullable|date_format:H:i',
            'arrive_time_end' => 'nullable|date_format:H:i',
            'arrival_flight_name' => 'nullable',
            'airline_id' => 'nullable',
            'dep_airport_id' => 'nullable',
            'arr_airport_id' => 'nullable',
            'terminal_id' => 'nullable',
            'arrival_flg' => 'nullable|boolean',
            'car_id' => 'nullable',
            'number' => 'nullable|numeric|digits:4',
            'car_color_id' => 'nullable',
            'size_type' => 'nullable|in:' . implode(',', CarSize::SIZE_TYPE_IDS),
            'num_members' => 'nullable|int',
            'car_caution_id' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        if(empty($this->label_tag)) {
            return;
        }
        $this->merge([
            'label_tag' => array_filter($this->label_tag),
        ]);
    }

    public function attributes()
    {
        return [
            'reserved' => '予約中',
            'load_today' => '本日入庫予定',
            'loaded' => '入庫中',
            'unload_plan_today' => '本日出庫予定',
            'pending' => '保留',
            'unloaded' => '出庫済',
            'reserve_code' => '予約コード',
            'receipt_code' => '受付コード',
            'reserve_date' => '予約日時',
            'agency_id' => '予約経路',
            'load_date_start' => '入庫日',
            'load_date_end' => '入庫日',
            'load_time_start' => '入庫時間',
            'load_time_end' => '入庫時間',
            'unload_date_plan_start' => '出庫予定日',
            'unload_date_plan_end' => '出庫予定日',
            'unload_date_start' => '出庫日',
            'unload_date_end' => '出庫日',
            'num_days_start' => '利用日数',
            'num_days_end' => '利用日数',
            'member_code' => '顧客コード',
            'name' => 'お客様氏名',
            'kana' => 'ふりがな',
            'user_num' => '利用回数',
            'label_tag' => 'ラベル',
            'zip' => '郵便番号',
            'tel' => '電話番号',
            'email' => 'Mail',
            'line_id' => 'LINE ID',
            'arrive_date_start' => '到着予定日',
            'arrive_date_end' => '到着予定日',
            'arrive_time_start' => '到着予定時間',
            'arrive_time_end' => '到着予定時間',
            'arrival_flight_name' => '到着便',
            'airline_id' => '航空会社',
            'dep_airport_id' => '出発空港',
            'arr_airport_id' => '到着空港',
            'terminal_id' => '到着ターミナル',
            'arrival_flg' => '到着日とお迎え日が異なる',
            'car_id' => '車種',
            'number' => '車番',
            'car_color_id' => '色',
            'size_type' => '区分',
            'num_members' => '人数',
            'car_caution_id' => '車両取扱',
        ];
    }
}
