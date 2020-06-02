<?php

use App\Types\AdmissionMode;
use App\Types\FundingType;
use App\Types\Gender;
use App\Types\RequestStatus;
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
            $table->string('phone')->nullable();
            $table->text('address', 251)->nullable();
            $table->enum('gender', Gender::values())->nullable();
            $table->enum('category', ReservationCategory::values())->nullable();
            $table->enum('admission_mode', AdmissionMode::values())->nullable();
            $table->enum('funding', FundingType::values())->nullable();
            $table->text('research_area', 501)->nullable();
            $table->string('enrolment_id', 30)->nullable();
            $table->date('registration_date')->nullable();
            $table->json('education_details')->nullable();
            $table->string('proposed_title')->nullable();
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
