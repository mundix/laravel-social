<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('causes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("slug");
            $table->string("name");
            $table->string("email");
            $table->boolean("is_promoted")->default(false);
            $table->longText("description")->nullable();
            $table->unsignedBigInteger("category_id");
            $table->string('location')->nullable();
            $table->enum('location_type',
                ['local', 'regional', 'national', 'global'])->default('local');
            $table->string("website")->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('category_causes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('causes');
    }
}
