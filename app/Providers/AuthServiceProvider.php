<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use App\Policies\ScholarProfilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('scholars.coursework.store', ScholarProfilePolicy::class . '@addCoursework');
        Gate::define('scholars.coursework.complete', ScholarProfilePolicy::class . '@markCourseworkCompleted');
        Gate::define('scholars.advisory_meetings.store', ScholarProfilePolicy::class . '@addAdvisoryMeeting');
    }
}
