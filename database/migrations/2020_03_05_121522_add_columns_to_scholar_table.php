<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToScholarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->unsignedBigInteger('cosupervisor_id')->nullable();
            $table->string('phone_no')->nullable();
            $table->text('address', 251)->nullable();
            $table->enum('category', array_keys(config('options.scholars.categories')))->nullable();
            $table->enum('admission_via', array_keys(config('options.scholars.admission_criterias')))->nullable();
            $table->text('research_area', 501)->nullable();
            $table->enum('gender', array_keys(config('options.scholars.genders')))->nullable();
            $table->date('enrollment_date')->nullable();
            $table->json('advisory_committee')->nullable();
            $table->json('education')->nullable();
            $table->json('old_cosupervisors')->nullable();
            $table->json('old_supervisors')->nullable();

            $table->foreign('cosupervisor_id')->references('id')->on('cosupervisors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholars', function (Blueprint $table) {
        });
    }
}
