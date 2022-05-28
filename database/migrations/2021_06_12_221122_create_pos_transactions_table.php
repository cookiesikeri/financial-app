<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('terminalId')->nullable();
            $table->string('rrn')->nullable();
            $table->string('pan')->nullable();
            $table->string('stan')->nullable();
            $table->decimal('amount');
            $table->unsignedInteger('cardExpiry')->nullable();
            $table->string('merchantId');
            $table->string('reference');
            $table->string('statusDescription');
            $table->dateTime('transactionDate');
            $table->enum('transactionType',['ussd','card'])->default('card');
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
        Schema::dropIfExists('pos_transactions');
    }
}
