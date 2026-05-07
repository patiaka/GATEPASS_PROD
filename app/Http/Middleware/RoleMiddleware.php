<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        // Vérifie si l’utilisateur possède au moins un des rôles donnés
        foreach ($roles as $role) {
            if ($user->hasAnyRole($roles)) {
                return $next($request);
            }
        }

        // L'utilisateur n'a pas le rôle approprié, vous pouvez personnaliser la réponse d'erreur selon vos besoins
        return abort(403);
    }
}
