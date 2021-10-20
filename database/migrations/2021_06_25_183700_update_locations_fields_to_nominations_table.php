<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocationsFieldsToNominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominates', function (Blueprint $table) {
            if(Schema::hasColumn('nominates', 'location')) {
                $table->dropColumn('location');
            }

            if(Schema::hasColumn('nominates', 'location_type')) {
                $table->dropColumn('location_type');
            }

            if(!Schema::hasColumn('nominates', 'location')) {
                $table->string('location')->nullable()->after('name');
            }
            if(!Schema::hasColumn('nominates', 'location_type')) {
                $table->enum('location_type',
                    ['local', 'regional', 'national', 'global'])->default('local')->after('location');
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
