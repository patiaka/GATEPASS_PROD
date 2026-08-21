<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Notification de réinitialisation de mot de passe, mise en FILE d'attente :
 * la requête « mot de passe oublié » répond immédiatement, l'email part en
 * arrière-plan (worker), comme les autres emails de l'application.
 */
final class ResetPasswordNotification extends BaseResetPassword implements ShouldQueue
{
    use Queueable;
}
