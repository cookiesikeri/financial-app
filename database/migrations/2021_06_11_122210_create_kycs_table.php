<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->string('first_name', 80)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->string('middle_name', 80)->nullable();
            $table->mediumText('address')->nullable();
            $table->mediumText('home_address')->nullable();
            $table->mediumText('proof_of_address_url')->nullable();
            $table->string('id_card_number', 80)->nullable();
            $table->mediumText('id_card_url')->nullable();
            $table->string('next_of_kin', 80)->nullable();
            $table->string('next_of_kin_contact')->nullable();
            $table->string('mother_maiden_name', 80)->nullable();
            $table->string('guarantor', 80)->nullable();
            $table->string('guarantor_contact')->nullable();
            $table->unsignedBigInteger('country_of_residence_id')->nullable();
            $table->unsignedBigInteger('country_of_origin_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('lga_id')->nullable();
            $table->string('city', 100)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_of_residence_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('country_of_origin_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('cascade');
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
        Schema::dropIfExists('kycs');
    }
}
