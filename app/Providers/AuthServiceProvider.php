<?php

namespace App\Providers;

use App\Models\Scholar;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\ScholarProfilePolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
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

    protected $policiesNamespace = 'App\\Policies';

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function ($user) {
            if (get_class($user) === User::class && $user->is_admin === true) {
                return true;
            }
        });

        Gate::guessPolicyNamesUsing(function ($class) {
            return $this->policiesNamespace . '\\' . class_basename($class) . 'Policy';
        });

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            $params = [
                'token' => $token,
                'email' => $notifiable->email,
            ];

            if ($notifiable instanceof Scholar) {
                $params[] = 'scholar';
            }

            return route('password.reset', $params);
        });

        $this->registerPolicies();
    }
}
