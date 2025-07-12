<?php

namespace App\Http\Requests\Manage\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class RegiSalesAccountBooksRequest extends FormRequest
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
            'register' => 'nullable|int',
            'entry_date' => 'nullable|date',
            'entry_time' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === 'all') {
                        return;
                    }
                    $d = \DateTime::createFromFormat('H:i', $value);
                    if (!($d && $d->format('H:i') === $value)) {
                        $fail('時刻は H:i 形式、または "all" を指定してください。');
                    }
                },
            ],
        ];
    }



    public function attributes()
    {
        return [
            'register' => 'レジ番号',
            'entry_date' => '日付',
            'entry_time' => '時刻',
        ];
    }
}
