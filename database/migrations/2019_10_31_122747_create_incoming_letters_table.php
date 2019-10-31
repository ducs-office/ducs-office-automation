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
            $table->string('serial_no');
            $table->string('received_id');
            $table->string('sender',50);
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('handover_id');
            $table->enum('priority',[1,2,3]);
            $table->string('subject',80);
            $table->text('description', 400);
            $table->timestamps();

            $table->foreign('recipient_id')->references('id')->on('users');
            $table->foreign('handover_id')->references('id')->on('users');
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
