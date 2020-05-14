<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarAdvisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_advisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained();
            $table->morphs('advisor');
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
        Schema::dropIfExists('scholar_advisor');
    }
}
