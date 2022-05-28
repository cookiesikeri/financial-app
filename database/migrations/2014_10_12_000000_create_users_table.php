<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 199);
            $table->string('email', 199)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 199);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->string('bvn', 50)->nullable();
            $table->string('phone', 50);
            $table->string('gLocatorID', 12)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->integer('personal_account')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->integer('verified')->nullable();
            $table->string('verified_otp', 191)->nullable();
            $table->string('provider', 191)->nullable();
            $table->foreignUuid('provider_id')->nullable();
            $table->string('personal_bank')->nullable();
            $table->string('image')->nullable();
            $table->date('dob')->nullable();
            $table->string('sex', 20)->nullable();
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
        Schema::dropIfExists('users');
    }
}
