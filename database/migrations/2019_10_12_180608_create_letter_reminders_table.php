<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pdf')->nullable();
            $table->string('scan')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('letter_id')->references('id')->on('outgoing_letters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_reminders');
    }
}
