<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id');
            $table->string('status');
            $table->string('smartcard_num');
            $table->decimal('amount');
            $table->decimal('amount_paid');
            $table->decimal('commission');
            $table->string('phone');
            $table->string('email');
            $table->string('payment_method');
            $table->string('payment_ref');
            $table->string('platform');
            $table->string('customer_name');
            $table->datetime('date_created');
            $table->string('bundle_name');
            $table->datetime('date_modified');
            $table->foreignUuid('service_id');
            $table->integer('user_id');
            $table->foreignUuid('tv_bundles_id');
            $table->string('access_token');
            $table->integer('transaction_trials')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_transactions');
    }
}
