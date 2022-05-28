<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('user_id');
            $table->string('last_name');
            $table->string('middlename')->nullable();
            $table->string('email')->nullable();
            $table->string('home_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('status')->default(0);
            $table->string('bvn')->nullable();
            $table->string('gender')->nullable();
            $table->string('educational_level')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('children')->nullable();
            $table->string('nok')->nullable();
            $table->string('nok_contact')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('city')->nullable();
            $table->string('residential_status')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->date('date_started')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('loan_amount')->nullable();
            $table->string('repayment_period')->nullable();
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
        Schema::dropIfExists('loans');
    }
}
