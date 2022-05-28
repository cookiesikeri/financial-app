<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCardRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_card_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id');
            $table->string('name');
            $table->string('card_type');
            $table->string('phone_number')->nullable();
            $table->string('address');
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('currency')->nullable();
            $table->bigInteger('amount_to_fund')->nullable();
            $table->boolean('physical_card');
            $table->boolean('virtual_card');
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
        Schema::dropIfExists('business_card_requests');
    }
}
