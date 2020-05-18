<?php

use App\Types\Designation;
use App\Types\TeacherStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileColumnsToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('college_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->string('affiliation')->nullable();
            $table->enum('status', TeacherStatus::values())->nullable();
            $table->enum('designation', Designation::values())->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
        });
    }
}
