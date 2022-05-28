<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sender');
            //$table->integer('amount');
            //$table->string('reason');
            $table->string('status');
            $table->string('transfer_code');
            $table->string('transfer_id');
            //$table->string('rec_acc_num');
            //$table->string('rec_bank_name');
            //$table->string('rec_acc_name');
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
        Schema::dropIfExists('bank_transfers');
    }
}
