<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseProgrammeRevisionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_programme_revision', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Biginteger('course_id')->unsigned()->index();
            $table->Biginteger('programme_revision_id')->unsigned()->index();
            $table->unsignedTinyInteger('semester');

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('programme_revision_id')->references('id')->on('programme_revisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_programme_revision');
    }
}
