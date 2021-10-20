<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconNameToInvolvementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('involvements', function (Blueprint $table) {
            $table->string('icon_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('involvements', function (Blueprint $table) {
            if (Schema::hasColumn('involvements', 'icon_name')) {
                $table->dropColumn('icon_name');
            }
        });
    }
}
