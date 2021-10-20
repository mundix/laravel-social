<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocationToCausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('causes', function (Blueprint $table) {
            if(Schema::hasColumn('causes', 'location')) {
                $table->dropColumn('location');
            }

            if(Schema::hasColumn('causes', 'location_type')) {
                $table->dropColumn('location_type');
            }

            if(!Schema::hasColumn('causes', 'location')) {
                $table->string('location')->nullable()->after('name');
            }
            if(!Schema::hasColumn('causes', 'location_type')) {
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
        Schema::table('causes', function (Blueprint $table) {
            if (Schema::hasColumn('causes', 'location')) {
                $table->dropColumn('location');
            }

            if (Schema::hasColumn('causes', 'location_type')) {
                $table->dropColumn('location_type');
            }
        });
    }
}
