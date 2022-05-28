<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('loan_transactions');
        Schema::dropIfExists('loan_balances');
        Schema::dropIfExists('loan_offers');

        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_id');
            $table->float('amount', 11, 2);

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
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
        Schema::dropIfExists('loan_repayments');

        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });
        Schema::create('loan_balances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });
        Schema::create('loan_offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });
    }
}
