<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCosupervisorScholarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cosupervisor_scholar_table', function (Blueprint $table) {
            $table->unsignedBigInteger('scholar_id');
            $table->unsignedBigInteger('cosupervisor_id');

            $table->primary(['scholar_id', 'cosupervisor_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
