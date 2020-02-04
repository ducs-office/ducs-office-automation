<?php

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_programme_revision_id');
            $table->unsignedBigInteger('teachers_profile_id');

            $table->foreign('teachers_profile_id')->references('id')->on('teachers_profile')->onDelete('cascade');
            $table->foreign('course_programme_revision_id')->references('id')->on('course_programme_revision')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teaching_details');
    }
}
