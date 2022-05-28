<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTvBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount');
            $table->foreignUuid('service_id');
            $table->string('code');
            $table->string('available');
            $table->string('allowance');
            $table->timestamps();
        });

        DB::table('tv_bundles')->insert([
            [
                'name' => 'DStv Compact French Plus (for Premium compact)',
                'amount' => 16100.00,
                'service_id' => 21,
                'code' => 51,
                'available' => 'Yes',
                'allowance' => 'FRN15E36,COMPE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact French Touch',
                'amount' => 10300.00,
                'service_id' => 21,
                'code' => 52,
                'available' => 'Yes',
                'allowance' => 'FRN7E36,COMPE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact Asian Add-on',
                'amount' => 14200.00,
                'service_id' => 21,
                'code' => 53,
                'available' => 'Yes',
                'allowance' => 'ASIADDE36,COMPE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact HDPVR/Xtraview',
                'amount' => 10500.00,
                'service_id' => 21,
                'code' => 54,
                'available' => 'Yes',
                'allowance' => 'HDPVRE36,COMPE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact Plus French Plus (For Premium, Compact+ and Compact only',
                'amount' => 20600.00,
                'service_id' => 21,
                'code' => 55,
                'available' => 'Yes',
                'allowance' => 'FRN15E36,COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact Plus French Touch',
                'amount' => 14800.00,
                'service_id' => 21,
                'code' => 56,
                'available' => 'Yes',
                'allowance' => 'FRN7E36,COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact Plus Asian Add-on',
                'amount' => 18700.00,
                'service_id' => 21,
                'code' => 57,
                'available' => 'Yes',
                'allowance' => 'ASIADDE36,COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'DStv Compact Plus HDPVR/XtraView',
                'amount' => 15000.00,
                'service_id' => 21,
                'code' => 58,
                'available' => 'Yes',
                'allowance' => 'HDPVRE36,COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Premium French Plus Extraview',
                'amount' => 29100.00,
                'service_id' => 21,
                'code' => 59,
                'available' => 'Yes',
                'allowance' => 'FRN15E36,PRWE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Premium french Touch',
                'amount' => 20800.00,
                'service_id' => 21,
                'code' => 60,
                'available' => 'Yes',
                'allowance' => 'FRN7E36,PRWE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Premium Asian Add-on',
                'amount' => 20600.00,
                'service_id' => 21,
                'code' => 61,
                'available' => 'Yes',
                'allowance' => 'ASIADDE36,PRWE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'DStv Premium HDPVR/XtraView',
                'amount' => 21000.00,
                'service_id' => 21,
                'code' => 62,
                'available' => 'Yes',
                'allowance' => 'HDPVRE36,PRWE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Premium Asia HDPVR/XtraView',
                'amount' => 23100.00,
                'service_id' => 21,
                'code' => 65,
                'available' => 'Yes',
                'allowance' => 'PRWASIE36,HDPVRE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Asian Bouquet HDPVR/Xtraview',
                'amount' => 8800.00,
                'service_id' => 22,
                'code' => 66,
                'available' => 'Yes',
                'allowance' => 'HDPVRE36,ASIAE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'DStv Compact',
                'amount' => 8000.00,
                'service_id' => 21,
                'code' => 72,
                'available' => 'Yes',
                'allowance' => 'COMPE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Compact Plus',
                'amount' => 12500.00,
                'service_id' => 21,
                'code' => 73,
                'available' => 'Yes',
                'allowance' => 'COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Premium',
                'amount' => 18500.00,
                'service_id' => 21,
                'code' => 74,
                'available' => 'Yes',
                'allowance' => 'PRWE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Premium Asia',
                'amount' => 20600.00,
                'service_id' => 21,
                'code' => 75,
                'available' => 'Yes',
                'allowance' => 'PRWASIE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Asian Bouquet Add on',
                'amount' => 6300.00,
                'service_id' => 21,
                'code' => 84,
                'available' => 'Yes',
                'allowance' => 'ASIAE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Yanga Bouquet E36',
                'amount' => 2665.00,
                'service_id' => 21,
                'code' => 85,
                'available' => 'Yes',
                'allowance' => 'NNJ136E36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Confam Bouquet E3',
                'amount' => 4720.00,
                'service_id' => 21,
                'code' => 86,
                'available' => 'Yes',
                'allowance' => 'NNJ2E36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Padi',
                'amount' => 1950.00,
                'service_id' => 21,
                'code' => 92,
                'available' => 'Yes',
                'allowance' => 'NLTESE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Great Wall Standalone Bouquet',
                'amount' => 1385.00,
                'service_id' => 21,
                'code' => 93,
                'available' => 'Yes',
                'allowance' => 'GWALLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Confam + Xtraview',
                'amount' => 7215.00,
                'service_id' => 21,
                'code' => 94,
                'available' => 'Yes',
                'allowance' => 'NNJ2E36,HDPVRE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Yanga + Xtraview',
                'amount' => 5165.00,
                'service_id' => 21,
                'code' => 95,
                'available' => 'Yes',
                'allowance' => 'NNJ1E36,HDPVRE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Padi + Xtraview',
                'amount' => 4450.00,
                'service_id' => 21,
                'code' => 96,
                'available' => 'Yes',
                'allowance' => 'NLTESE36,HDPVRE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'DStv Box Office',
                'amount' => 600.00,
                'service_id' => 21,
                'code' => 97,
                'available' => 'Yes',
                'allowance' => '',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Add-on-French 11 Bouquet E36',
                'amount' => 3360.00,
                'service_id' => 21,
                'code' => 100,
                'available' => 'Yes',
                'allowance' => 'FRN11E36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Add-on-DStv French Touch Bouquet E36',
                'amount' => 2400.00,
                'service_id' => 21,
                'code' => 101,
                'available' => 'Yes',
                'allowance' => 'FRN7E36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Add-on-DStv French Touch Add-on Bouquet E36',
                'amount' => 2400.00,
                'service_id' => 21,
                'code' => 102,
                'available' => 'Yes',
                'allowance' => 'FRN7E36,COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Add-on-DStv HDPVR/Xtraview E36',
                'amount' => 2600.00,
                'service_id' => 21,
                'code' => 104,
                'available' => 'Yes',
                'allowance' => 'HDPVRE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'French Plus Add-on',
                'amount' => 8200.00,
                'service_id' => 21,
                'code' => 105,
                'available' => 'Yes',
                'allowance' => 'FRN7E36,COMPLE36',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GOtv Max',
                'amount' => 3700.00,
                'service_id' => 23,
                'code' => 84,
                'available' => 'Yes',
                'allowance' => 'GOTVMAX',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GOtv Max',
                'amount' => 3700.00,
                'service_id' => 22,
                'code' => 78,
                'available' => 'Yes',
                'allowance' => 'GOTVMAX',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GOtv Jinja Bouquet',
                'amount' => 1740.00,
                'service_id' => 22,
                'code' => 89,
                'available' => 'Yes',
                'allowance' => 'GOTVNJ1',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GOtv Smallie - Yearly',
                'amount' => 6300.00,
                'service_id' => 22,
                'code' => 109,
                'available' => 'Yes',
                'allowance' => 'GOLTANL',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GOtv Smallie - quarterly',
                'amount' => 2200.00,
                'service_id' => 22,
                'code' => 108,
                'available' => 'Yes',
                'allowance' => 'GOLITE',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GOtv Smallie - Monthly',
                'amount' => 900.00,
                'service_id' => 22,
                'code' => 107,
                'available' => 'Yes',
                'allowance' => 'GOHAN',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'StarTimes Nova',
                'amount' => 1000.00,
                'service_id' => 23,
                'code' => '79',
                'available' => 'Yes',
                'allowance' => 'Nova',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'StarTimes Basic',
                'amount' => 1800.00,
                'service_id' => 23,
                'code' => '80',
                'available' => 'Yes',
                'allowance' => 'GOTVMAX',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'StarTimes Smart',
                'amount' => 2300.00,
                'service_id' => 23,
                'code' => '81',
                'available' => 'Yes',
                'allowance' => 'Smart',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'StarTimes Classic',
                'amount' => 2600.00,
                'service_id' => 23,
                'code' => '82',
                'available' => 'Yes',
                'allowance' => 'Classic',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'StarTimes Super',
                'amount' => 4300.00,
                'service_id' => 23,
                'code' => '83',
                'available' => 'Yes',
                'allowance' => 'Super',
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
        Schema::dropIfExists('tv_bundles');
    }
}
