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
            $table->string('serial_no')->unique();
            $table->string('received_id');
            $table->string('sender',50);
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('handover_id')->nullable();
            $table->enum('priority',[1,2,3])->nullable();
            $table->string('subject',80);
            $table->text('description', 400)->nullable();
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
