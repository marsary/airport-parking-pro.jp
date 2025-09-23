<?php

namespace App\Http\Requests\Manage;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class EntryDateRequest extends FormRequest
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
            'agency_code' => 'nullable|string|exists:agencies,code|max:100',
            'agency_id' => 'nullable|int',
            'load_date' => 'required|date',
            'load_time' => 'required|date_format:G:i',
            'unload_date_plan' => 'required|date|after:load_date',
            'unload_time_plan' => 'nullable|date_format:H:i',
            'num_days' => 'required|int',
            'coupon_ids' => 'nullable|array',
            'coupon_ids.*' => 'int',
        ];
    }

    protected function prepareForValidation()
    {
        if(isset($this->agency_code)) {
            $agency = DB::table('agencies')->where('code', $this->agency_code)->first();
        }
        $this->merge([
            'agency_id' => isset($agency) ? $agency->id: null,
        ]);
        $coupon_ids = [];
        if(!empty($this->coupon_ids)) {
            $coupon_ids = explode(',', $this->coupon_ids);
        }
        $this->merge([
            'coupon_ids' => $coupon_ids,
        ]);

        if ($this->unload_time_plan) {
            try {
                $this->merge([
                    'unload_time_plan' => Carbon::parse($this->unload_time_plan)->format('H:i'),
                ]);
            } catch (\Throwable $th) {
                // パースに失敗した場合はバリデーションに任せる
            }
        }

        if ($this->load_time) {
            try {
                $this->merge([
                    'load_time' => Carbon::parse($this->load_time)->format('G:i'),
                ]);
            } catch (\Throwable $th) {
                // パースに失敗した場合はバリデーションに任せる
            }
        }
    }

    public function attributes()
    {
        return [
            'agency_code' => '代理店ID',
            'load_date' => '入庫日',
            'load_time' => '入庫時間',
            'unload_date_plan' => '出庫予定日',
            'unload_time_plan' => '出庫予定時間',
            'num_days' => '利用日数',
            'coupon_ids' => 'クーポン選択',
        ];
    }
}
