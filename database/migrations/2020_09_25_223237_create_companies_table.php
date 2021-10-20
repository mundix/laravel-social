<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string("slug");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("name");
            $table->longText("description")->nullable();
            $table->longText("about")->nullable();
            $table->string("about_title")->nullable();
            $table->string("about_link")->nullable();
            $table->string("location")->nullable();
            $table->string("caption")->nullable();
            $table->enum('status',['enabled','disabled'])->default('enabled');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
