<?php

namespace App\Http\Requests\Manage;

use App\Rules\FlightNoDateRule;
use App\Rules\PhoneRule;
use App\Rules\ZipcodeRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
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
            'name'	=> 'required',
            'kana'	=> 'required',
            'tel'	=> ['required','max:15', new PhoneRule],
            'zip'	=> ['nullable','max:8', new ZipcodeRule],
            'email'	=> 'required|email',
            'receipt_address'	=> 'nullable|max:100',
            'member_memo'	=> 'nullable',
            'reserve_memo'	=> 'nullable',
            'reception_memo'	=> 'nullable',
            'car_maker_id' => 'nullable',
            'car_id' => 'required',
            'car_color_id' => 'required',
            'car_number' => 'required|numeric|digits:4',
            'flight_no' => ['nullable', new FlightNoDateRule],
            'arrive_date' => 'nullable|date',
            'arrive_time' => 'nullable|date_format:H:i',
            'num_members' => 'nullable|numeric',
            'good_ids' => 'nullable|array',
            'good_ids.*' => 'int',
            'modal_good_ids' => 'nullable|array',
            'modal_good_ids.*' => 'int',
            'good_nums' => 'nullable|array',
            'good_nums.*' => 'int',
            'modal_good_nums' => 'nullable|array',
            'modal_good_nums.*' => 'int',
            'car_caution_ids' => 'nullable|array',
            'car_caution_ids.*' => 'int',
            'cancel_btn' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $good_ids = [];
        if(!empty($this->good_ids)) {
            $good_ids = explode(',', $this->good_ids);
        }
        $this->merge([
            'good_ids' => $good_ids,
        ]);
        $good_nums = [];
        if(!empty($this->good_nums)) {
            $good_nums = json_decode($this->good_nums, true);
        }
        $this->merge([
            'good_nums' => $good_nums,
        ]);
    }

    public function attributes()
    {
        return [
            'name' => '氏名',
            'kana' => 'ふりがな',
            'tel' => '携帯番号',
            'zip' => '郵便番号',
            'email' => 'メールアドレス',
            'receipt_address' => '領収書の宛名',
            'member_memo' => '顧客メモ',
            'reserve_memo' => '予約メモ',
            'reception_memo' => '受付メモ',
            'car_maker_id' => 'メーカー',
            'car_id' => '車種',
            'car_color_id' => '色',
            'car_number' => 'ナンバー',
            'flight_no' => '到着便',
            'arrive_date' => '到着日',
            'arrive_time' => '到着予定時間',
            'num_members' => 'ご利用人数',
            'good_ids' => 'オプション選択',
            'good_nums' => 'オプション数量',
            'car_caution_ids' => '取扱注意メモ',
        ];
    }
}
