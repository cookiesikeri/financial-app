<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsFromTransaveAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('role_id')->after('email')->nullable();
            });
        }
        if (!Schema::hasColumn('power_transactions', 'date_modified')) {
            Schema::table('power_transactions', function (Blueprint $table) {
                $table->dateTime('date_modified')->after('access_token')->nullable();
            });
        }
        if (!Schema::hasColumn('power_transactions', 'date_created')) {
            Schema::table('power_transactions', function (Blueprint $table) {
                $table->dateTime('date_created')->after('date_modified')->nullable();
            });
        }
//        if (!Schema::hasColumn('vendors', 'status')) {
//            Schema::table('vendors', function (Blueprint $table) {
//                $table->dateTime('status')->after('id')->nullable();
//            });
//        }
//        if (!Schema::hasColumn('vendors', 'deleted_at')) {
//            Schema::table('vendors', function (Blueprint $table) {
//                $table->dateTime('deleted_at')->after('status')->nullable();
//            });
//        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
