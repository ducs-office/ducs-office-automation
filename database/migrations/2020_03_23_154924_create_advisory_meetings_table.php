<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvisoryMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisory_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scholar_id');
            $table->date('date');
            $table->string('minutes_of_meeting_path');
            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
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
        Schema::dropIfExists('advisory_meetings');
    }
}
