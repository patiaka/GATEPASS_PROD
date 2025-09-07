<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enum\RoleEnum;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Obtenez l'objet utilisateur à partir de la requête (assurez-vous d'avoir la fonctionnalité d'authentification configurée)
        $user = $request->user();

        // Vérifiez si l'utilisateur a le rôle requis
        if ($user && $this->hasRole($user, $role)) {
            return $next($request); // L'utilisateur a le rôle approprié, laissez-le accéder à la route suivante
        }

        // L'utilisateur n'a pas le rôle approprié, vous pouvez personnaliser la réponse d'erreur selon vos besoins
        return abort(403);
    }

    /**
     * Check if the user has the specified role.
     *
     * @param  \App\Model\User  $user
     */
    private function hasRole(User $user, string $role): bool
    {
        // Utilisez les fonctions de la classe User pour vérifier le rôle de l'utilisateur
        return match ($role) {
            RoleEnum::ADMIN->value => $user->isAdmin(),
            RoleEnum::GM->value => $user->isGm() || $user->isAdmin(),
            RoleEnum::HOD->value => $user->isHod() || $user->isAdmin() || $user->isGm(),
            RoleEnum::USER->value => $user->isUser() || $user->isHod() || $user->isAdmin() || $user->isGm(),
            RoleEnum::Security->value => $user->isUser() || $user->isAdmin() || $user->isSecurity(),
            default => false, // Rôle non pris en charge
        };
    }
}
