<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('orginating_fee', 191)->nullable();
            $table->string('interest', 191)->nullable();
            $table->string('loan_amount', 191)->nullable();
            $table->string('total_repayment', 191)->nullable();
            $table->date('due_date')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('loan_offers');
    }
}
