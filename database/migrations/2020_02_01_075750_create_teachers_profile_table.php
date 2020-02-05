<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers_profile', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->primary();
            $table->string('phone_no')->nullable();
            $table->text('address', 251)->nullable();
            $table->enum('designation', array_keys(config('options.teachers.designations')))->nullable();
            $table->unsignedBigInteger('college_id')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers_profile');
    }
}
