<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToPosRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_requests', function (Blueprint $table) {
            $table->dropColumn('bvn');
            $table->enum('status',['processing','processed','rejected'])->default('processing')->change();
            $table->unsignedInteger('no_of_pos')->default(1);
            $table->dropColumn('date_of_birth');
            $table->dropColumn('email');
            $table->dropColumn('home_address');
            $table->dropColumn('phone_number');
            $table->dropColumn('middlename');
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('mother_maiden_name');
            $table->dropColumn('gender');
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
            //
        });
    }
}
