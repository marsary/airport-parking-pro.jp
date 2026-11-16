<?php

namespace App\Http\Requests\Manage\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 管理画面 パスワード再設定メール送信
 */
class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['email' => 'ユーザーID（メールアドレス）'];
    }
}
