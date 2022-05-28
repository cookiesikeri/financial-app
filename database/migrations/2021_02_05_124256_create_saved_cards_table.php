<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('auth_code');
            $table->string('card_type');
            $table->string('last4');
            $table->string('exp_month');
            $table->string('exp_year');
            $table->string('bin');
            $table->string('bank');
            $table->string('channel');
            $table->string('signature')->unique();
            $table->string('reuseable');
            $table->string('country_code');
            $table->string('account_name')->nullable()->default('John Doe');
            $table->uuid('user_id');
            $table->string('user_email');
            $table->timestamps();

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
        Schema::dropIfExists('saved_cards');
    }
}
