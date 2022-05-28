<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirtimeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtime_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id');
            $table->string('status', 45);
            $table->string('phone', 45);
            $table->string('email', 45);
            $table->decimal('amount', 7, 2);
            $table->decimal('amount_paid', 7, 2)->nullable();
            $table->decimal('commission', 2, 0);
            $table->string('payment_method', 45);
            $table->string('payment_ref', 45);
            $table->string('platform', 45);
            $table->datetime('date_created');
            $table->datetime('date_modified');
            $table->foreignUuid('user_id');
            $table->foreignUuid('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airtime_transactions');
    }
}
