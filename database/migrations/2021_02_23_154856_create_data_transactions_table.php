<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id');
            $table->string('status');
            $table->string('phone');
            $table->string('email');
            $table->decimal('amount');
            $table->decimal('amount_paid');
            $table->decimal('commission');
            $table->string('payment_method');
            $table->string('payment_ref');
            $table->string('platform');

            $table->foreignUuid('user_id');
            $table->foreignUuid('service_id');
            $table->foreignUuid('data_bundles_id');
            $table->datetime('date_created');
            $table->datetime('date_modified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_transactions');
    }
}
