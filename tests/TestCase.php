<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    public function signIn($user = null, $role = 'admin')
    {
        if (!$user) {
            $user = create(User::class);
        }

        $user->assignRole(Role::firstOrCreate(['name' => $role]));

        $this->be($user);

        return $user;
    }

    public function mergeFormFields($data, $overrides)
    {
        return array_map(function ($value) {
            if (is_callable($value)) {
                return $value();
            }
            return $value;
        }, array_merge($data, $overrides));
    }
}
