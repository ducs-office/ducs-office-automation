<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CollegesProgrammes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colleges_programmes', function(Blueprint $table) {
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('programme_id');
            
            $table->primary(['college_id', 'programme_id']);
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colleges_programmes');
    }
}
