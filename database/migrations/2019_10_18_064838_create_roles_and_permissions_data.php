<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table(config('permission.table_names.permissions'))->insert(
            collect(config('permission.static.permissions'))->map(function ($operations, $resource) {
                return collect($operations)->map(function ($operation) use ($resource) {
                    return ['name' => "$resource:$operation", 'guard_name' => 'web'];
                });
            })->flatten(1)->toArray()
        );

        DB::table(config('permission.table_names.roles'))->insert(
            collect(config('permission.static.roles'))->keys()->map(function ($role) {
                return ['name' => $role, 'guard_name' => 'web'];
            })->toArray()
        );

        $role_permissions = collect(config('permission.static.roles'))
            ->map(function ($permissions, $role) {
                $role = DB::table(config('permission.table_names.roles'))
                    ->where('name', $role)->first();

                if (! $role) {
                    return false;
                }

                return collect($permissions)->map(function ($operations, $resource) use ($role) {
                    return collect($operations)->map(function ($operation) use ($resource, $role) {
                        $permission = DB::table(config('permission.table_names.permissions'))
                            ->whereName("$resource:$operation")
                            ->first();

                        if (! $permission) {
                            return false;
                        }

                        return ['role_id' => $role->id, 'permission_id' => $permission->id];
                    })->filter();
                })->toArray();
            })->filter()->flatten(2)->toArray();

        DB::table(config('permission.table_names.role_has_permissions'))->insert($role_permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('role_has_permissions')->delete();

        DB::table('permissions')->delete();

        DB::table('roles')->delete();
    }
}
