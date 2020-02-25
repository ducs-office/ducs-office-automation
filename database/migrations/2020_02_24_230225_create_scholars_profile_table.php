<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarsProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholars_profile', function (Blueprint $table) {
            $table->unsignedBigInteger('scholar_id')->primary();
            $table->string('phone_no')->nullable();
            $table->text('address', 251)->nullable();
            $table->enum('category', array_keys(config('options.scholars.categories')))->nullable();
            $table->enum('admission_via', array_keys(config('options.scholars.admission_criterias')))->nullable();
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
        Schema::dropIfExists('scholars_profile');
    }
}
