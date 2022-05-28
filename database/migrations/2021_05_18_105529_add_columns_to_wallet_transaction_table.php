<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToWalletTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('receiver_account_number')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('transfer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['receiver_account_number', 'receiver_name', 'description', 'transfer']);
        });
    }
}
