<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::shouldBeStrict(! app()->isProduction());
        // URL::forceHttps(app()->isProduction());

        Gate::define('update-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                // Une demande approuvée ou expirée n'est plus modifiable (personne, admin inclus)
                if ($Request->isApproved() || $Request->isExpired()) {
                    return false;
                }

                if ((int) $Request->user_id === $user->id && $Request->isPending()) {
                    return true;
                }

                if ($user->isAdmin()) {
                    return true;
                }

                return false;
            }
        });
        Gate::define('download-request', function (User $user, MaterialRequest|CarRequest $request) {
            // Seul un request approuvé peut être téléchargé
            if (! $request->isApproved()) {
                return false;
            }

            // L'auteur de la demande peut télécharger
            if ((int) $user->id === (int) $request->user_id) {
                return true;
            }

            // Admin et Sécurité peuvent télécharger
            if ($user->isAdmin() || $user->isSecurity()) {
                return true;
            }

            return false;
        });

        Gate::define('show-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                // if ($user->isGm() || $user->isHod() || $user->isAdmin() || $user->isSecurity()) {
                if ($user->isApprover() || $user->isAdmin() || $user->isSecurity()) {
                    return true;
                }

                if ($user->isUser()) {
                    return true;
                }
            }
        });

       Gate::define('delete-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                if ($user->isAdmin()) {
                    return true;
                }
                
                if ((int) $Request->user_id === (int) $user->id && $Request->isPending()) {
                    return true;
                }

                return false;
            }
        });

        Gate::define('action-approved-request', function (User $user) {
            // return $user->isGm() || $user->isHod();
            return $user->isApprover();
        });
    }
}
