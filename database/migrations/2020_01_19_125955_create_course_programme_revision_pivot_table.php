<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseProgrammeRevisionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_programme_revision', function (Blueprint $table) {
            $table->Biginteger('course_id')->unsigned()->index();
            $table->Biginteger('programme_revision_id')->unsigned()->index();
            $table->unsignedTinyInteger('semester');
            $table->primary(['course_id', 'programme_revision_id'], 'id');

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
