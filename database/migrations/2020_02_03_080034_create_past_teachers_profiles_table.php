<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePastTeachersProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_teachers_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('designation', 5);
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('teacher_id');
            $table->date('valid_from');
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('past_teachers_profiles');
    }
}
