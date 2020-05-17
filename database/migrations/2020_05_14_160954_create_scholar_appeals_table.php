<?php

use App\Models\ScholarAppeal;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars')->onDelete('cascade');
            $table->string('proposed_title')->nullable();
            $table->enum('status', ScholarAppealStatus::values())->default(ScholarAppealStatus::APPLIED);
            $table->enum('type', ScholarAppealTypes::values());
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
        Schema::dropIfExists('scholar_appeals');
    }
}
