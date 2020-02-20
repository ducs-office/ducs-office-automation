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
        Schema::create('teaching_details', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('programme_revision_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedTinyInteger('semester');

            $table->foreign('teacher_id')->references('teacher_id')->on('teachers_profile')->onDelete('cascade');
            $table->foreign('programme_revision_id')->references('id')->on('programme_revisions')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
