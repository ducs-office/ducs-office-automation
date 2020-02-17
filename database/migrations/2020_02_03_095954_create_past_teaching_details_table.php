<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePastTeachingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_teaching_details', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('past_teachers_profile_id');
            $table->unsignedBigInteger('course_programme_revision_id');

            $table->foreign('past_teachers_profile_id')->references('id')->on('past_teachers_profiles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('past_teaching_details');
    }
}
