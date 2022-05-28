<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAndRecreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('loans');
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_account_id');
            $table->float('amount', 11, 2);
            $table->float('balance', 11, 2);
            $table->float('originating_fee', 11, 2)->default(0);
            $table->float('interest', 11, 2)->default(0);
            $table->boolean('is_approved')->default(true);
            $table->timestamp('expiry_date');

            $table->foreign('loan_account_id')->references('id')->on('loan_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('loans');
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
    }
}
