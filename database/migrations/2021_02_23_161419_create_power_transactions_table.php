<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id');
            $table->string('token');
            $table->string('status');
            $table->string('meter_num');
            $table->decimal('amount');
            $table->decimal('amount_paid');
            $table->decimal('commission');
            $table->string('phone');
            $table->string('email');
            $table->string('payment_method');
            $table->string('payment_ref');
            $table->string('platform');
            $table->string('customer_name');
            // $table->timestamps('date_modified');
            $table->timestamps();
            $table->string('units');
            $table->foreignUuid('service_id');
            $table->foreignUuid('user_id');
            $table->boolean('access_token')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('power_transactions');
    }
}
