<?php

namespace App\Providers;

use App\Policies\ProfilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        // Policies are auto-discovered via App\Models\{Model} => App\Policies\{Model}Policy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Profile uses the User model, which is already mapped to UserPolicy.
        // Expose dedicated abilities so ProfilePolicy can authorize self-service actions.
        Gate::define("viewProfile", [ProfilePolicy::class, "view"]);
        Gate::define("updateProfile", [ProfilePolicy::class, "update"]);
        Gate::define("deleteProfile", [ProfilePolicy::class, "delete"]);

        // Allow Super Admins to bypass all authorization
        Gate::before(function ($user, $ability) {
            return $user->hasRole("Super Admin") ? true : null;
        });
    }
}
