<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

final class SetLocale
{
    /** Langues disponibles. */
    public const SUPPORTED = ['en', 'fr'];

    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale'));

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale); // dates relatives (« il y a 2 heures »)

        return $next($request);
    }
}
