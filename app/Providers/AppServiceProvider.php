<?php

namespace App\Providers;

use App\Models\CarRequest;
use App\Models\User;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Gate::define('update-material-request', function (User $user, MaterialRequest $materialRequest) {
            return ($user->id === $materialRequest->user_id and $materialRequest->isPending()) || $user->isAdmin();
        });

        Gate::define('show-material-request', function (User $user, MaterialRequest $materialRequest) {
            if ($user->isGm() || $user->isHod() || $user->isAdmin()) {
                return  true;
            } elseif ($user->isUser() and $user->id === $materialRequest->user_id) {
                return true;
            }
        });

        Gate::define('update-car-request', function (User $user, CarRequest $carRequest) {
            return ($user->id === $carRequest->user_id and $carRequest->isPending()) || $user->isAdmin();
        });

        Gate::define('show-car-request', function (User $user, CarRequest $carRequest) {
            if ($user->isGm() || $user->isHod() || $user->isAdmin()) {
                return  true;
            } elseif ($user->isUser() and $user->id === $carRequest->user_id) {
                return true;
            }
        });

        Gate::define('action-approved-request', function (User $user) {
            return $user->isGm() || $user->isHod();
        });
    }
}
