<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessNameRegNumToPosRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_requests', function (Blueprint $table) {
            $table->string('business_type')->nullable();
            $table->string('business_reg_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_requests', function (Blueprint $table) {
            $table->dropColumn(['business_type', 'business_reg_num']);
        });
    }
}
