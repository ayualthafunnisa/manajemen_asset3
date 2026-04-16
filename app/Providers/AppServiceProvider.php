<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Gate: apakah user boleh melakukan CRUD ──
        // Hanya super_admin yang TIDAK boleh CRUD
        Gate::define('crud', function ($user) {
            return $user && !in_array($user->role, ['super_admin']);
        });

        // ── Blade directive @canCrud / @endCanCrud ──
        Blade::if('canCrud', function () {
            $user = auth()->user();
            return $user && !in_array($user->role, ['super_admin']);
        });
    }
}