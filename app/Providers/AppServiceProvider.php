<?php

namespace App\Providers;

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
        Gate::define('update-material-request', function ($user, $materialRequest) {
            // return Auth::user()->is === $materialRequest->user_id;
        });

        Gate::define('action-material-request', function ($user, $materialRequest) {
            // return Auth::user()->is === $materialRequest->user_id;
        });
    }
}
