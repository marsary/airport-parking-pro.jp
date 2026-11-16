<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 管理画面（スタッフ／usersテーブル）用 パスワード再設定メール
 */
class ManagePasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('manage.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
        $expire = config('auth.passwords.users.expire');

        return (new MailMessage)
            ->subject('【管理画面】パスワードの再設定')
            ->greeting($notifiable->name . ' 様')
            ->line('管理画面のパスワード再設定のリクエストを受け付けました。')
            ->line('下のボタンから新しいパスワードを設定してください。')
            ->action('パスワードを再設定する', $url)
            ->line("このリンクの有効期限は{$expire}分です。")
            ->line('お心当たりがない場合は、このメールを破棄してください。パスワードは変更されません。')
            ->salutation(config('app.name'));
    }
}
