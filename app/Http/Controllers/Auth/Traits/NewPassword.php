<?php
namespace App\Http\Controllers\Auth\Traits;

use App\Models\Member;
use Illuminate\Support\Facades\Password;

trait NewPassword
{
    protected function sendPasswordResetLink(string $email, string $redirectRoute = null, $isFormAuth = false)
    {
        $status = Password::broker('members')->sendResetLink(
            ['email' => $email],
            function($user, $token) use($redirectRoute, $isFormAuth) {
                /** @var Member $user */
                $user->redirectRoute = $redirectRoute;
                if($isFormAuth) {
                    $user->isFormAuth = $isFormAuth;
                }
                // Once we have the reset token, we are ready to send the message out to this
                // user with a link to reset their password. We will then redirect back to
                // the current URI having nothing set in the session to indicate errors.
                $user->sendPasswordResetNotification($token);
            }
        );

        return $status;
    }
}
