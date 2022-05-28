<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('last_name', 30);
            $table->unsignedBigInteger('educational_qualification_id')->nullable();
            $table->integer('marital_status')->default(1);
            $table->integer('number_of_children')->nullable();
            $table->string('next_of_kin', 60)->nullable();
            $table->string('next_of_kin_phone', 25)->nullable();
            $table->string('emergency_contact_name', 60)->nullable();
            $table->string('emergency_contact_number', 25)->nullable();
            $table->mediumText('other_information')->nullable();
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('lga_id');
            $table->string('city', 40)->nullable();
            $table->mediumText('address')->nullable();
            $table->unsignedBigInteger('residential_status_id')->nullable();
            $table->unsignedBigInteger('employment_status_id')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employment_date')->nullable();
            $table->unsignedBigInteger('monthly_income_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('educational_qualification_id')->references('id')->on('educational_qualifications')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('cascade');
            $table->foreign('residential_status_id')->references('id')->on('residential_statuses')->onDelete('cascade');
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('cascade');
            $table->foreign('monthly_income_id')->references('id')->on('monthly_incomes')->onDelete('cascade');

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
        Schema::dropIfExists('loan_accounts');
    }
}
