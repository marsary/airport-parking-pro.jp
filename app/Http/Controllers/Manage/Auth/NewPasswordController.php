<?php

namespace App\Http\Controllers\Manage\Auth;

use App\Enums\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * 管理画面 パスワード再設定（メールのリンクから新しいパスワードを登録）
 */
class NewPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('manage.auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function store(ResetPasswordRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $active = User::where('email', $data['email'])
            ->where('status', GeneralStatus::Enabled->value)
            ->whereNull('deleted_at')
            ->exists();

        $status = $active
            ? Password::broker('users')->reset(
                $data,
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                        'updated_by' => $user->id,
                    ])->save();

                    event(new PasswordReset($user));
                }
            )
            : Password::INVALID_TOKEN;

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('manage.login')
                ->with('status', 'パスワードを再設定しました。新しいパスワードでログインしてください。');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'このパスワード再設定用URLは無効か、有効期限が切れています。お手数ですが、もう一度「パスワードを忘れた方」からやり直してください。']);
    }
}
