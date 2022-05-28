<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('amount');
            $table->integer('balance')->nullable();
            $table->integer('projected_saving')->nullable();
            $table->string('cycle')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('duration')->nullable();
            $table->date('next_save')->nullable();
            $table->date('start_save')->nullable();
            $table->date('end_date')->nullable();
            $table->string('time_elapsed')->nullable();
            $table->string('status')->nullable();
            $table->string('access')->nullable();
            $table->string('type')->nullable();
            $table->string('savings_state')->nullable();
            $table->integer('card_added')->nullable()->default(0);
            $table->uuid('userId');
            $table->integer('mid_payment')->nullable()->default(0);
            $table->integer('mid_payment_amount')->nullable()->default(0);
            $table->string('card_signature')->nullable();
            $table->integer('last_turn')->nullable()->default(0);
            $table->integer('next_turn')->nullable()->default(1);
            $table->integer('num_of_members')->nullable();
            $table->timestamps();

            $table->index('userId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings');
    }
}
