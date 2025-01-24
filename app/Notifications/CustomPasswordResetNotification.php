<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CustomPasswordResetNotification extends Notification
{
    use Queueable;
    public $token;
    public $redirectRoute;
    public $isFormAuth;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $redirectRoute = null, $isFormAuth = false)
    {
        $this->token = $token;
        $this->redirectRoute = $redirectRoute;
        $this->isFormAuth = $isFormAuth;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if($this->isFormAuth) {
            $route = 'form.password.reset';
        } else {
            $route = 'password.reset';
        }
        return (new MailMessage)
            ->subject(Lang::get('パスワードの再設定'))
            ->greeting('こんにちは')
            ->line('パスワード再設定のリクエストを受け付けました。')
            ->action(Lang::get('パスワード再設定'), route($route, [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
                'mode' => 'edit',
                'redirectRoute' => $this->redirectRoute,
            ]))
            ->line(Lang::get('このパスワード再設定リンクの有効期限は :count 分です.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line('パスワード再設定のリクエストにお心当たりがない場合は、このメールを無視してください。')
            ;

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
