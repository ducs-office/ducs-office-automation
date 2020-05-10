<?php

use App\Types\Designation;
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
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', TeacherStatus::values());
            $table->enum('designation', Designation::values());
            $table->foreignId('college_id');
            $table->foreignId('programme_revision_id');
            $table->foreignId('course_id');
            $table->unsignedTinyInteger('semester');

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
