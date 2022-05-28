<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_savings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');
            $table->integer('amount');
            $table->integer('balance')->nullable()->default(0);
            $table->integer('projected_saving')->nullable();
            $table->string('cycle')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('duration')->nullable();
            $table->date('next_save')->nullable();
            $table->date('start_save')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('card_added')->nullable()->default(0);
            $table->uuid('user_id');
            $table->integer('mid_payment')->nullable()->default(0);
            $table->integer('mid_payment_amount')->nullable()->default(0);
            $table->string('card_signature')->nullable();
            $table->timestamps();

            $table->index('account_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_savings');
    }
}
