<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToGroupSavings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_savings', function (Blueprint $table) {
            $table->bigInteger('balance')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('group_savings', 'balance')){
            Schema::table('group_savings', function (Blueprint $table) {
                $table->dropColumn('balance');
            });
        }
    }
}
