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
        Schema::create('cosupervisor_scholar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->contrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->date('started_on')->default(today());
            $table->date('ended_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scholar_supervisor');
    }
}
