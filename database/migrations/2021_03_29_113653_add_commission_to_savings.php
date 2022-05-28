<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionToSavings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->double('commission_percent', 2, 2)->nullable()->default(0.00);
            $table->bigInteger('commission_balance')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumns('saving_transactions', ['commission_percent', 'commission_balance'])){
            Schema::table('savings', function (Blueprint $table) {
                $table->dropColumn('commission_percent');
                $table->dropColumn('commission_balance');
            });
        }
    }
}
