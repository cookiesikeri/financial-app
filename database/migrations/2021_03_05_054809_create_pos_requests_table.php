<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middlename')->nullable();
            $table->string('home_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->integer('status')->default(0);
            $table->string('bvn')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mother_maiden_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('lga')->nullable();
            $table->string('means_of_id')->nullable();
            $table->string('identification_doc')->nullable();
            $table->string('passport')->nullable();
            $table->string('id_number')->nullable();
            $table->string('occupation')->nullable();
            $table->string('job_title')->nullable();
            $table->string('business_licence')->nullable();
            $table->string('utility_billdoc')->nullable();
            $table->string('other_doc')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('account_number')->nullable();


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
        Schema::dropIfExists('pos_requests');
    }
}
