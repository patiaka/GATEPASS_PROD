<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('update-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                return ($user->id === $Request->user_id and $Request->isPending()) || $user->isAdmin();
            }
        });

        Gate::define('download-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                return ($user->id === $Request->user_id and $Request->isApproved()) || $user->isAdmin() || $user->isSecurity();
            }
        });

        Gate::define('show-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                if ($user->isGm() || $user->isHod() || $user->isAdmin() || $user->isSecurity()) {
                    return true;
                }
                if ($user->isUser() and $user->id === $Request->user_id) {
                    return true;
                }
            }
        });

        Gate::define('delete-request', function (User $user, MaterialRequest|CarRequest $Request) {
            if ($Request instanceof CarRequest || $Request instanceof MaterialRequest) {
                return ($user->id === $Request->user_id and $Request->isPending()) || $user->isAdmin();
            }
        });

        Gate::define('action-approved-request', function (User $user) {
            return $user->isGm() || $user->isHod();
        });
    }
}
