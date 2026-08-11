<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use App\Observers\RequestAuditObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
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

        // Audit : connexions + modifications des demandes
        Event::listen(Login::class, LogSuccessfulLogin::class);
        CarRequest::observe(RequestAuditObserver::class);
        MaterialRequest::observe(RequestAuditObserver::class);

        Gate::define('update-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                // Une demande approuvée, expirée ou annulée n'est plus modifiable (personne, admin inclus)
                if ($Request->isApproved() || $Request->isExpired() || $Request->isCancelled()) {
                    return false;
                }

                // Le propriétaire peut éditer tant que la demande est en attente,
                // ou pour la corriger et la renvoyer si elle a été rejetée.
                if ((int) $Request->user_id === $user->id && ($Request->isPending() || $Request->isRejected())) {
                    return true;
                }

                if ($user->isAdmin()) {
                    return true;
                }

                return false;
            }
        });
        Gate::define('cancel-request', function (User $user, MaterialRequest|CarRequest $request) {
            // Seul l'administrateur peut annuler. Une demande approuvée n'est plus
            // annulable ; ni une demande déjà annulée ou expirée.
            return $user->isAdmin()
                && ! $request->isApproved()
                && ! $request->isCancelled()
                && ! $request->isExpired();
        });

        Gate::define('download-request', function (User $user, MaterialRequest|CarRequest $request) {
            // Un laissez-passer ne s'imprime que s'il est approuvé…
            if (! $request->isApproved()) {
                return false;
            }

            // …et seul l'administrateur peut l'imprimer.
            return $user->isAdmin();
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
                // Une demande approuvée n'est plus supprimable (personne, admin inclus)
                if ($Request->isApproved()) {
                    return false;
                }

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
