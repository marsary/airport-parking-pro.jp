<?php

namespace App\Http\Controllers\Manage\Auth;

use App\Enums\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\Auth\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * 管理画面 パスワードを忘れた方（再設定メールの送信）
 */
class PasswordResetLinkController extends Controller
{
    /** 登録有無にかかわらず同じ文言を返す（メールアドレスの存在確認に使われないように） */
    private const SENT_MESSAGE = '入力されたユーザーIDが登録されている場合、パスワード再設定用のメールを送信しました。メールをご確認ください。';

    public function create(): View
    {
        return view('manage.auth.forgot-password');
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $email = $request->validated('email');

        // 有効（status=1）かつ削除されていないスタッフにだけ送信する
        $active = User::where('email', $email)
            ->where('status', GeneralStatus::Enabled->value)
            ->whereNull('deleted_at')
            ->exists();

        if ($active) {
            $result = Password::broker('users')->sendResetLink(['email' => $email]);
            if ($result !== Password::RESET_LINK_SENT) {
                // 連続送信（60秒以内）など。画面には同じ文言を表示する
                Log::info('管理画面パスワード再設定メール未送信', ['email' => $email, 'result' => $result]);
            }
        } else {
            Log::info('管理画面パスワード再設定：対象ユーザーなし', ['email' => $email]);
        }

        return back()->with('status', self::SENT_MESSAGE);
    }
}
