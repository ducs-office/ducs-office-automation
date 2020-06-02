<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarSupervisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_supervisor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained('users');
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
        Schema::dropIfExists('scholar_supervisors');
    }
}
