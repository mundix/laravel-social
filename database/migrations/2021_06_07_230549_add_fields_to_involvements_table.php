<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToInvolvementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('involvements', function (Blueprint $table) {

            Schema::dropIfExists('involvements');

            if (!Schema::hasTable('involvements')) {
                Schema::create('involvements', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('company_id')->nullable();
                    $table->foreignId('cause_id')->nullable();
                    $table->foreignId('employee_id')->nullable();
                    $table->unsignedDouble('hours')->default(0);
                    $table->unsignedDouble('donations')->default(0);
                    $table->unsignedDouble('matches')->default(0);
                    $table->enum('status', ['pending', 'publish', 'draft'])->default('pending');
                    $table->timestamps();
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('involvements');
    }
}
