<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_letters', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_no')->unique();
            $table->date('date');
            $table->enum('type', ['Bill', 'Notesheet', 'General']);
            $table->unsignedBigInteger('sender_id');
            $table->string('recipient', 100);
            $table->string('subject', 100);
            $table->text('description', 400)->nullable();
            $table->float('amount')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outgoing_letters');
    }
}
