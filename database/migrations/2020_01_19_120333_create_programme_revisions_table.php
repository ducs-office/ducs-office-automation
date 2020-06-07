<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_revisions', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('programme_id');
            $table->timestamp('revised_at');

            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programme_revisions');
    }
}
