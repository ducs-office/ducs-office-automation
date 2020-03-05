<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupervisorProfileToScholars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_profile_id')->nullable()->index();
            $table->foreign('supervisor_profile_id')->references('id')->on('supervisor_profiles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropForeign('supervisor_profile_id');
            $table->dropColumn('supervisor_profile_id');
        });
    }
}
