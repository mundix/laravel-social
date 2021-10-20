<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToNominatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominates', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->string('location_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nominates', function (Blueprint $table) {
            if (Schema::hasColumn('nominates', 'location')) {
                $table->dropColumn('location');
            }

            if (Schema::hasColumn('nominates', 'location_type')) {
                $table->dropColumn('location_type');
            }
        });
    }
}
