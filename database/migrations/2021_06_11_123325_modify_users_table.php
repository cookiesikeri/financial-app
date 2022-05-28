<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provider_id', 'personal_account', 'two_factor_secret', 'two_factor_recovery_codes', 'personal_bank', 'provider']);
            //           DB::statement('ALTER TABLE users CHANGE COLUMN transaction_pin transaction_pin VARCHAR(20) NULL DEFAULT NULL AFTER password');
            $table->float('withdrawal_limit', 11, 2)->default(100000)->after('sex');
            $table->tinyInteger('shutdown_level')->after('withdrawal_limit')->default(0);
            $table->tinyInteger('account_type_id')->after('shutdown_level')->default(1);

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_id')->nullable()->after('password');
            $table->string('personal_account')->nullable()->after('provider_id');
            $table->string( 'two_factor_secret')->nullable()->after('personal_account');
            $table->string('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->string('personal_bank')->nullable()->after('two_factor_recovery_codes');
            $table->string('provider')->nullable()->after('personal_bank');
            $table->dropColumn('withdrawal_limit');
            $table->dropColumn('shutdown_level');
            $table->dropColumn('account_type_id');
        });
    }
}
