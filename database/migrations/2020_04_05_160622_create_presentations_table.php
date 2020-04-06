<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('publication_id');
            $table->string('city');
            $table->string('country');
            $table->date('date');
            $table->boolean('scopus_indexed')->default(0);
            $table->enum('venue', array_keys(config('options.scholars.presentation_venues')));
            $table->timestamps();

            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presentations');
    }
}
