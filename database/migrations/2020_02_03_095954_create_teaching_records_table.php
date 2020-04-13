<?php

use App\Types\TeacherStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_records', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('valid_from');
            $table->unsignedBigInteger('teacher_id');
            $table->enum('designation', TeacherStatus::values());
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('programme_revision_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedTinyInteger('semester');

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('CASCADE');
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
        Schema::dropIfExists('teaching_records');
    }
}
