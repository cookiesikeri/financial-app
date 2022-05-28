<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateServiceTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

        });

        DB::table('service_type')->insert([
            [
                'name' => 'AIRTIME',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DATA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'POWER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_type');
    }
}
