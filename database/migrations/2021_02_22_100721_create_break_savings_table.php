<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_savings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reason')->nullable();
            $table->string('explanation')->nullable();
            $table->uuid('user_id');
            $table->uuid('account_id');
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
        Schema::dropIfExists('break_savings');
    }
}
