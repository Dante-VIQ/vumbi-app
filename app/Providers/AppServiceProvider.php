<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

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
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Only attempt to set up the gate bypass if the permissions table exists
        // This prevents errors during fresh migrations where the table doesn't exist yet.
        if ($this->permissionTableExists()) {
            // Engineer = Full Access (Super-Admin style)
            Gate::before(function ($user, $ability) {
                if ($user->hasRole('engineer')) {
                    return true; // Bypass all permissions & gates
                }

                return null; // Continue with normal permission checks
            });
        }

        // Development helpers
        if ($this->app->environment('local', 'testing')) {
            Model::preventLazyLoading();
        }
    }

    /**
     * Centralized role setup (idempotent and cache-aware)
     */
    protected function setupRolesAndPermissions(): void
    {

        // skip if the roles table doesn't exist yet (e.g. during initial migrations)
        // if (!Schema::hasColumns('roles', 'guard_name')) {
        //     return;
        // }
        // Always clear Spatie cache before making changes
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create core roles (add more as needed)

        // Assign special roles from config/env (idempotent)
        $this->assignSpecialRoles();

        // Clear cache again after changes
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Assign engineer (full access) and master emails if any remain
     */
    protected function permissionTableExists(): bool
    {
        try {
            return Schema::hasTable('roles') && Schema::hasTable('model_has_roles');
        } catch (\Exception $e) {
            return false;
        }
    }
}
