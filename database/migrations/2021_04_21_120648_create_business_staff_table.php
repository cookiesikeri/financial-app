<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreignUuid('user_id')->unique();
            $table->foreignUuid('business_id');
            $table->string('role');
            $table->bigInteger('salary');
            $table->integer('tax');
            $table->boolean('suspended');
            $table->boolean('deactivated');
            $table->boolean('on_payroll');
            $table->integer('bonus');
            $table->date('dob');
            $table->text('address');
            $table->enum('pay_cycle', ['daily', 'monthly', 'weekly']);
            $table->string('phone_number');
            $table->timestamps();

            $table->index(['business_id', 'on_payroll', 'pay_cycle']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_staff');
    }
}
