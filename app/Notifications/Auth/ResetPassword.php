<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    use Queueable;
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('public.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

}
