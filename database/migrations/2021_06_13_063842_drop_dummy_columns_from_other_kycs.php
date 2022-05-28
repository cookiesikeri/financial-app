<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDummyColumnsFromOtherKycs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_kycs', function (Blueprint $table) {
            $table->dropColumn(['owner_first_name', 'owner_last_name', 'gender', 'business_name', 'kin_name', 'kin_phone', 'bvn']);
        });

        Schema::dropIfExists('user_kycs');

        Schema::table('loan_accounts', function (Blueprint $table) {
            $table->dropForeign('loan_accounts_state_id_foreign');
            $table->dropForeign('loan_accounts_lga_id_foreign');
            $table->dropIndex('loan_accounts_state_id_foreign');
            $table->dropIndex('loan_accounts_lga_id_foreign');

            $table->dropColumn(['first_name', 'last_name', 'middle_name', 'address', 'next_of_kin', 'next_of_kin_phone', 'state_id', 'lga_id', 'city']);
        });
        Schema::rename('loan_accounts', 'loan_kycs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_kycs', function (Blueprint $table) {
            $table->string('owner_first_name')->nullable();
            $table->string('owner_last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('business_name')->nullable();
            $table->string('kin_name')->nullable();
            $table->string('kin_phone')->nullable();
            $table->string('bvn')->nullable();
        });

        Schema::create('user_kycs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });

        Schema::rename('loan_kycs', 'loan_accounts');
        Schema::table('loan_accounts', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('address')->nullable();
            $table->string('next_of_kin')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('lga_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();

            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });

    }
}
