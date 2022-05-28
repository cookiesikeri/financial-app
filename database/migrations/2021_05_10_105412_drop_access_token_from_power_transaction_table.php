<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAccessTokenFromPowerTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('power_transactions', 'access_token')){
            Schema::table('power_transactions', function (Blueprint $table) {
                $table->dropColumn('access_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('power_transactions', function (Blueprint $table) {
            //
        });
    }
}
