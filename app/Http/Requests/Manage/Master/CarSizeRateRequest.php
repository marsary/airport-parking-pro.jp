<?php

namespace App\Http\Requests\Manage\Master;

use Illuminate\Foundation\Http\FormRequest;

class CarSizeRateRequest extends FormRequest
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
        // dd($this->all());
        return [
            'carsize_price_rates' => 'array',
            'carsize_price_rates.*' => 'nullable|decimal:0,2|lt:100',
        ];
    }

    public function attributes()
    {
        $attributes = [];

        if ($this->has('carsize_price_rates')) {
            foreach ($this->input('carsize_price_rates') as $key => $value) {
                $attributes["carsize_price_rates.{$key}"] = "{$key}";
            }
        }

        return $attributes;
    }
}
