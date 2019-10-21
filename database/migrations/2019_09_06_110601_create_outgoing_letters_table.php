<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutgoingLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creator_id');
            $table->date('date');
            $table->enum('type', ['Bill', 'Notesheet', 'General']);
            $table->string('subject', 80);
            $table->string('recipient', 50);
            $table->unsignedBigInteger('sender_id');
            $table->text('description', 400)->nullable();
            $table->float('amount')->nullable();
            $table->string('pdf')->nullable();
            $table->string('scan')->nullable();
            $table->string('serial_no')->unique();
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
