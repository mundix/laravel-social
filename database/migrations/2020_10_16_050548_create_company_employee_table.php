<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id")->nullable();
            $table->unsignedBigInteger("employee_id")->nullable();
            $table->timestamps();

	        $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
	        $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_employee');
    }
}
