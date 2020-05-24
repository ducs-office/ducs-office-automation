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
            $table->text('paper_title');
            $table->morphs('author');
            $table->string('document_path');
            $table->boolean('is_published')->default(false);
            $table->text('name')->nullable();
            $table->date('date')->nullable();
            $table->smallInteger('volume')->nullable();
            $table->json('publisher')->nullable();
            $table->integer('number')->nullable();
            $table->string('indexed_in')->nullable();
            $table->string('page_numbers')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('paper_link')->nullable();
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
