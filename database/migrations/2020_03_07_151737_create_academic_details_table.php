<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['publication', 'presentation']);
            $table->string('authors');
            $table->text('title');
            $table->text('conference');
            $table->smallInteger('volume')->nullable();
            $table->string('publisher');
            $table->date('date');
            $table->integer('number')->nullable();
            $table->string('indexed_in');
            $table->string('city');
            $table->string('country');
            $table->string('page_numbers');
            $table->unsignedBigInteger('scholar_id');
            $table->timestamps();

            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_details');
    }
}
