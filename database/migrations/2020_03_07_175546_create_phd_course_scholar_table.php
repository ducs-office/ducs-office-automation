<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhdCourseScholarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phd_course_scholar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('phd_course_id');
            $table->unsignedBigInteger('scholar_id');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('phd_course_id')->references('id')->on('phd_courses')->onDelete('cascade');
            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phd_course_scholar');
    }
}
