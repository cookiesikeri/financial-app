<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIntegerColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_balances', function (Blueprint $table) {            
            $table->float('balance')->change();
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->float('balance')->change();

        });

        Schema::table('savings', function (Blueprint $table) {
            $table->float('balance')->change();
            $table->float('amount')->change();
            $table->float('commission_balance')->change();
        });

        Schema::table('agent_savings', function (Blueprint $table) {
            $table->float('balance')->change();
            $table->float('amount')->change();
            $table->float('mid_payment_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
