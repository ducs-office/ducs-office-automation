<?php

use App\Types\PublicationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', PublicationType::values());
            $table->text('name');
            $table->text('paper_title');
            $table->date('date');
            $table->smallInteger('volume')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('number')->nullable();
            $table->string('indexed_in');
            $table->string('page_numbers');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->morphs('main_author');
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
        Schema::dropIfExists('publications');
    }
}
