<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status');
            $table->decimal('service_charge');
            $table->float('convenience_fee')->default(0);
            $table->decimal('commission');
            $table->integer('service_type_id');
            $table->integer('api_id');
            $table->timestamps();
            $table->integer('minimum_value')->default(0);
            $table->integer('maximum_value')->default(0);
        });

        DB::table('service')->insert([
            [
                'name' => 'MTN',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 1,
                'api_id' => 1,
                'minimum_value' => 50,
                'maximum_value' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Glo',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 1,
                'api_id' => 1,
                'minimum_value' => 50,
                'maximum_value' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Airtel',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 1,
                'api_id' => 1,
                'minimum_value' => 50,
                'maximum_value' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => '9mobile',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 1,
                'api_id' => 1,
                'minimum_value' => 50,
                'maximum_value' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'MTN',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 2,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Etisalat',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 2,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Airtel',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 2,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Smile',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 2,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Spectranet',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 2,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'AEDC',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 20.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 600,
                'maximum_value' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'AEDC Postpaid',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 20.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 1000,
                'maximum_value' => 21000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Ikeka Electric Bill Payment',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Ikeja Token Purchase',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Eko Prepaid',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Eko Postpaid',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Ibadan Disco Prepaid',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Kano Electricity Disco',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Kaduna Electricity Disco',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 20.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'PhED Electricity',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Kaduna Electricity Disco Postpaid',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 3,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'DSTV',
                'status' => 1,
                'service_charge' => 100.00,
                'convenience_fee' => 20.00,
                'commission' => 0.00,
                'service_type_id' => 4,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'GOTV',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 20.00,
                'commission' => 0.00,
                'service_type_id' => 4,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'name' => 'StarTimes',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 55.00,
                'commission' => 0.00,
                'service_type_id' => 4,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 50000,
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
        Schema::dropIfExists('service');
    }
}
