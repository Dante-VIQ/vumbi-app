<?php

namespace App\Providers;

use App\Services\AI\AIContentService;
use App\Services\ApiClients\OpenStreetMapClient;
use App\Services\ApiClients\OpenTripMapClient;
use App\Services\BonusArriveService;
use App\Services\Builders\CityPageBuilder;
use App\Services\FlightAggregatorService;
use App\Services\GeoService;
use App\Services\PlacesService;
use App\Services\Search\QueryClassifierService;
use App\Services\Search\RouteDecisionService;
use App\Services\Search\SearchOrchestratorService;
use App\Services\TravelPayouts\FlightService;
use App\Services\TravelPayouts\HotelService;
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
                // --- Core orchestration (singleton – one per request/worker) ---
        $this->app->singleton(SearchOrchestratorService::class);

        // --- Flight aggregation ---
        $this->app->singleton(FlightAggregatorService::class);

        // --- City building ---
        $this->app->singleton(CityPageBuilder::class);

        // --- AI (consolidated) ---
        $this->app->singleton(AIContentService::class);

        // --- Classifier & Router (stateless, but singleton for consistency) ---
        $this->app->singleton(QueryClassifierService::class);
        $this->app->singleton(RouteDecisionService::class);

        // --- Data providers (singleton – they hold no state) ---
        $this->app->singleton(OpenStreetMapClient::class);
        $this->app->singleton(OpenTripMapClient::class);
        $this->app->singleton(HotelService::class);
        $this->app->singleton(FlightService::class);            // still used by aggregator
        $this->app->singleton(BonusArriveService::class);      // still used by aggregator
        $this->app->singleton(PlacesService::class);
        $this->app->singleton(GeoService::class);
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
