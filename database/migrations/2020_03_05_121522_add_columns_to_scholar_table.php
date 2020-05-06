<?php

use App\Types\AdmissionMode;
use App\Types\Gender;
use App\Types\ReservationCategory;
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
            $table->nullableMorphs('cosupervisor_profile');
            $table->string('phone_no')->nullable();
            $table->text('address', 251)->nullable();
            $table->enum('gender', Gender::values())->nullable();
            $table->enum('category', ReservationCategory::values())->nullable();
            $table->enum('admission_mode', AdmissionMode::values())->nullable();
            $table->text('research_area', 501)->nullable();
            $table->date('enrollment_date')->nullable();
            $table->json('advisory_committee')->nullable();
            $table->json('education_details')->nullable();
            $table->json('old_cosupervisors')->nullable();
            $table->json('old_supervisors')->nullable();
            $table->json('old_advisory_committees')->nullable();
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
