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
            $table->bigIncrements('id');
            $table->string('phone_no')->nullable();
            $table->text('address', 251)->nullable();
            $table->enum('designation', array_keys(config('options.teachers.designations')))->nullable();
            $table->string('ifsc')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('bank_branch', 251)->nullable();
            $table->unsignedBigInteger('college_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null');
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
        Schema::dropIfExists('teachers_profile');
    }
}
