<?php

namespace App\Http\Controllers\Manage\Auth;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Auth\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * 管理画面 パスワード変更（ログイン中のスタッフ本人）
 */
class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('manage.auth.password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user('web');

        $user->forceFill([
            'password' => Hash::make($request->validated('password')),
            'updated_by' => $user->id,
        ])->save();

        // セッション固定化対策としてセッションIDを再生成
        $request->session()->regenerate();

        Log::info('管理画面パスワード変更', ['user_id' => $user->id]);

        return redirect()->route('manage.password.edit')
            ->with('success', 'パスワードを変更しました。次回から新しいパスワードでログインしてください。');
    }
}
