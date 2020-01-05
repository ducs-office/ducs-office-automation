<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('serial_no')->unique(); // Serial no assigned to In bound letters
            $table->string('received_id'); // ID on the received letter
            $table->string('sender', 100);
            $table->unsignedBigInteger('recipient_id');
            $table->enum('priority', [1,2,3])->nullable();
            $table->string('subject', 100);
            $table->text('description', 400)->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();

            $table->foreign('recipient_id')->references('id')->on('users');
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
        Schema::dropIfExists('incoming_letters');
    }
}
