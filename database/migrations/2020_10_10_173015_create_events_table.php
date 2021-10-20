<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string("slug");
            $table->string("name");
            $table->integer('participants')->default(0);
            $table->text("description")->nullable();
            $table->double('global_amount')->default(0);
            $table->double('total_amount')->default(0);
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft','pending', 'enabled',' suspended'])->default('draft');
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
        Schema::dropIfExists('events');
    }
}
