<?php

use App\LeaveStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scholar_id');
            $table->date('from');
            $table->date('to');
            $table->string('reason', 190);
            $table->string('document_path');
            $table->unsignedBigInteger('extended_leave_id')->nullable();
            $table->enum('status', LeaveStatus::values())->default(LeaveStatus::APPLIED);

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
        Schema::dropIfExists('leaves');
    }
}
