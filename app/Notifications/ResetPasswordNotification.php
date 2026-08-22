<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification de réinitialisation de mot de passe :
 *  - EN FILE d'attente (la requête « mot de passe oublié » répond instantanément) ;
 *  - habillée avec le logo, comme les autres emails de l'application.
 */
final class ResetPasswordNotification extends BaseResetPassword implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(config('app.name').' — Reset your password')
            ->view('emails.password-reset', [
                'name' => $notifiable->name ?? '',
                'resetUrl' => $resetUrl,
                'expire' => $expire,
                'company' => config('app.name'),
            ]);
    }
}
