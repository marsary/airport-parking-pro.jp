<?php

namespace App\Http\Requests\Manage\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * 管理画面 パスワード再設定（新しいパスワードの登録）
 */
class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'ユーザーID（メールアドレス）',
            'password' => '新しいパスワード',
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => '新しいパスワードと確認用パスワードが一致しません。',
        ];
    }
}
