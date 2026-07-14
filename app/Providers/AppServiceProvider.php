<?php

namespace App\Providers;

use App\Models\Blog;
use App\Policies\BlogPolicy;
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
use App\View\Composers\SeoComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
$this->app->singleton(SearchOrchestratorService::class);
        $this->app->singleton(CityPageBuilder::class);
        $this->app->singleton(FlightAggregatorService::class);
        $this->app->singleton(AIContentService::class);
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
        Gate::policy(Blog::class, BlogPolicy::class);
        Gate::define('create-blog', function ($user, ...$arguments) {
            return app(BlogPolicy::class)->create($user, ...$arguments);
        });
        Gate::define('update-blog', function ($user, ...$arguments) {
            return app(BlogPolicy::class)->update($user, ...$arguments);
        });
        Gate::define('delete-blog', function ($user, ...$arguments) {
            return app(BlogPolicy::class)->delete($user, ...$arguments);
        });

        // Development helpers
        if ($this->app->environment('local', 'testing')) {
            Model::preventLazyLoading();
        }

        View::composer('*', function ($view) {
        $view->with('organizationSchema', [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => "Vumbi Ventures",
            "url" => url('/'),
            "logo" => asset('images/vumbi-ventures-logo.png'),
            "sameAs" => [
                "https://www.facebook.com/share/g/1MVnVFXkMQ/",
                "https://linkedin.com/company/vumbi-ventures",
                "https://instagram.com/vumbiventures"
            ]
        ]);

        $view->with('websiteSchema', [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => "Vumbi Ventures",
            "url" => url('/'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => url('/discover') . "?q={search_term_string}",
                "query-input" => "required name=search_term_string"
            ]
        ]);
    });

    View::composer('*', SeoComposer::class);
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
        * Assign special roles like 'master' from config or environment variables
        */
    protected function assignSpecialRoles(): void
    {        $masterEmail = env('MASTER_EMAIL');
        if ($masterEmail) {
            $masterUser = \App\Models\User::where('email', $masterEmail)->first();
            if ($masterUser && !$masterUser->hasRole('master')) {
                $masterUser->assignRole('master');
            }
        }

        $engineerEmail = env('ENGINEER_EMAIL');
        if ($engineerEmail) {
            $engineerUser = \App\Models\User::where('email', $engineerEmail)->first();
            if ($engineerUser && !$engineerUser->hasRole('engineer')) {
                $engineerUser->assignRole('engineer');
            }
        }
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
