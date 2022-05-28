<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPosRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_requests', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable();
            $table->string('nearest_bus_stop')->nullable();
            $table->boolean('has_business')->nullable();
            $table->string('business_name')->nullable();
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
            $table->dropColumn(['user_id', 'nearest_bus_stop', 'has_business', 'business_name']);
        });
    }
}
