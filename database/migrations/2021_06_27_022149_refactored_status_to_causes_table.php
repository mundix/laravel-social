<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactoredStatusToCausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('causes', function (Blueprint $table) {

            if(Schema::hasColumn('causes', 'status')) {
                $table->dropColumn('status');
            }
            if(!Schema::hasColumn('causes', 'status')) {
                $table->enum('status', ['nominate', 'pending', 'draft', 'approved', 'rejected'])->default("approved")->after('website');
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
            if(Schema::hasColumn('causes', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
