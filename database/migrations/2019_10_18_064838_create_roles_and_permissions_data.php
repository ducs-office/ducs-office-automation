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
        DB::table('roles')->insert([
            [ 'name' => 'admin_staff', 'guard_name' => 'web' ],
            [ 'name' => 'teacher', 'guard_name' => 'web' ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')
            ->whereIn('name', ['admin_staff', 'teacher'])
            ->delete();
    }
}
