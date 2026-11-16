<?php

namespace App\Http\Requests\Manage\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * 管理画面 パスワード変更
 */
class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('web') !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', 'confirmed', 'different:current_password', Password::min(8)->letters()->numbers()],
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => '現在のパスワード',
            'password' => '新しいパスワード',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => '現在のパスワードが正しくありません。',
            'password.different' => '新しいパスワードには、現在のパスワードと異なるものを指定してください。',
            'password.confirmed' => '新しいパスワードと確認用パスワードが一致しません。',
        ];
    }
}
