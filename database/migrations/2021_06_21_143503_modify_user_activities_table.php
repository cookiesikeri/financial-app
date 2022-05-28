<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->string('activity', 50)->after('user_id')->nullable();
            $table->string('type', 40)->nullable()->change();
            $table->string('city', 150)->after('type')->nullable();
            $table->string('region', 80)->after('city')->nullable();
            $table->string('country', 80)->after('region')->nullable();
            $table->string('latitude', 20)->after('country')->nullable();
            $table->string('longitude', 20)->after('latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropColumn('activity');
            $table->string('type')->after('user_id')->change();
            $table->dropColumn('city');
            $table->dropColumn('region');
            $table->dropColumn('country');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
