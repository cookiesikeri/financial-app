<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('state', 191);
            $table->string('capital', 191);
            $table->timestamps();
        });

        DB::table('states')->insert([
            [
                'state' => 'Abia',
                'capital' => 'Umuahia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Adamawa',
                'capital' => 'Yola',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Akwa Ibom',
                'capital' => 'Uyo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Anambra',
                'capital' => 'Awka',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Bauchi',
                'capital' => 'Bauchi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Bayelsa',
                'capital' => 'Yenagoa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Benue',
                'capital' => 'Markudi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Borno',
                'capital' => 'Maiduguri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Cross River',
                'capital' => 'Calabar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Delta',
                'capital' => 'Asaba',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Ebonyi',
                'capital' => 'Abakaliki',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Edo',
                'capital' => 'Benin City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Ekiti',
                'capital' => 'Ado Ekiti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Enugu',
                'capital' => 'Enugu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'FCT',
                'capital' => 'Abuja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Gombe',
                'capital' => 'Gombe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Imo',
                'capital' => 'Owerri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Jigawa',
                'capital' => 'Dutse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Kaduna',
                'capital' => 'Kaduna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Kano',
                'capital' => 'Kano',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Katsina',
                'capital' => 'Katsina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Kebbi',
                'capital' => 'Birnin Kebbi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Kogi',
                'capital' => 'Lokoja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Kwara',
                'capital' => 'Ilorin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Lagos',
                'capital' => 'Ikeja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Nasarawa',
                'capital' => 'Lafia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Niger',
                'capital' => 'Minna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Ogun',
                'capital' => 'Abeokuta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Ondo',
                'capital' => 'Akure',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Osun',
                'capital' => 'Osogbo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Oyo',
                'capital' => 'Ibadan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Pleatau',
                'capital' => 'Jos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Rivers',
                'capital' => 'Port Harcourt',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'state' => 'Sokoto',
                'capital' => 'Sokoto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Taraba',
                'capital' => 'Jalingo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Yobe',
                'capital' => 'Damaturu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'state' => 'Zamfara',
                'capital' => 'Gusau',
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
        Schema::dropIfExists('states');
    }
}
