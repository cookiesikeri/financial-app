<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDataBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount');
            $table->timestamps();
            $table->integer('service_id');
            $table->integer('code');
        });

        DB::table('data_bundles')->insert([
            [
                'name' => 'SmileVoice ONLY 135 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 160,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Flexi Weekly Plan 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 162,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB MidNite Plan 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 163,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB MidNite Plan 1500',
                'amount' => 1500.0,
                'service_id' => 8,
                'code' => 164,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB Weekend Only Plan 1500',
                'amount' => 1500.0,
                'service_id' => 8,
                'code' => 165,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Smile Voice ONLY 430 3000',
                'amount' => 3000.0,
                'service_id' => 8,
                'code' => 166,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'ValuePlus Plan 3000',
                'amount' => 3000.0,
                'service_id' => 8,
                'code' => 167,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '7GB ValuePlus Plan 4000',
                'amount' => 4000.0,
                'service_id' => 8,
                'code' => 168,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '11GB ValuePlus Plan 5000',
                'amount' => 5000.0,
                'service_id' => 8,
                'code' => 169,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '15GB ValuePlus Plan 6000',
                'amount' => 6000.0,
                'service_id' => 8,
                'code' => 170,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '21GB ValuePlus Plan 8000',
                'amount' => 8000.0,
                'service_id' => 8,
                'code' => 171,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '10GB Anytime plan 8000',
                'amount' => 8000.0,
                'service_id' => 8,
                'code' => 172,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Lite Plan 10000',
                'amount' => 10000.0,
                'service_id' => 8,
                'code' => 173,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '31GB ValuePlus Plan 11000',
                'amount' => 11000.0,
                'service_id' => 8,
                'code' => 174,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'UnlimitedEssential Plan 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 175,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '30GB BumpaValue Plan 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 176,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '20GB Anytime plan 16000',
                'amount' => 16000.0,
                'service_id' => 8,
                'code' => 177,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited PremiumPlan 19800',
                'amount' => 19800.0,
                'service_id' => 8,
                'code' => 178,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '60GB BumpaValue Plan 30000',
                'amount' => 30000.0,
                'service_id' => 8,
                'code' => 179,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '50GB Anytime Plan 36000',
                'amount' => 36000.0,
                'service_id' => 8,
                'code' => 180,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '80GB BumpaValue plan 40000',
                'amount' => 40000.0,
                'service_id' => 8,
                'code' => 181,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '100GB Anytime Plan 70000',
                'amount' => 70000.0,
                'service_id' => 8,
                'code' => 182,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '200GB Anytime Plan 135000',
                'amount' => 135000.0,
                'service_id' => 8,
                'code' => 183,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'FlexiWeekly plan 350',
                'amount' => 350.0,
                'service_id' => 8,
                'code' => 184,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'SmileVoice ONLY plan 65 500',
                'amount' => 500.0,
                'service_id' => 8,
                'code' => 185,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5G ValuePlus Plan 2.15',
                'amount' => 2.1500,
                'service_id' => 8,
                'code' => 186,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '7GB ValuPlus 3.22',
                'amount' => 3.2200,
                'service_id' => 8,
                'code' => 187,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '20GB AnytimePlan 16.12',
                'amount' => 16.1200,
                'service_id' => 8,
                'code' => 188,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1.5GB ValuePlus Plan 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 189,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'FlexiWeekly plan 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 190,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1.5GB ValuePlus Plan 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 161,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5GB ValuePlus Plan 3070',
                'amount' => 3070.0,
                'service_id' => 8,
                'code' => 191,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '7GB ValuePlus Plan 4090',
                'amount' => 4090.0,
                'service_id' => 8,
                'code' => 192,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '20GB Anytime Plan 16380',
                'amount' => 16380.0,
                'service_id' => 8,
                'code' => 193,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'SmileVoice ONLY 65 510',
                'amount' => 510.0,
                'service_id' => 8,
                'code' => 194,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'SmileVoice ONLY 135 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 195,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB MidNite Plan 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 196,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB MidNite Plan 1530',
                'amount' => 1530.0,
                'service_id' => 8,
                'code' => 197,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB Weekend ONLY Plan 1530',
                'amount' => 1530.0,
                'service_id' => 8,
                'code' => 198,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'SmileVoice ONLY 430 3070',
                'amount' => 3070.0,
                'service_id' => 8,
                'code' => 199,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '11GB ValuePlus Plan 5110',
                'amount' => 5110.0,
                'service_id' => 8,
                'code' => 200,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '15GB ValuePlus Plan 6140',
                'amount' => 6140.0,
                'service_id' => 8,
                'code' => 201,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '21Gb ValuePlus Plan 8190',
                'amount' => 8190.0,
                'service_id' => 8,
                'code' => 202,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '10GB Anytime Plan 8190',
                'amount' => 8190.0,
                'service_id' => 8,
                'code' => 203,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Lite Plan 10230',
                'amount' => 10230.0,
                'service_id' => 8,
                'code' => 204,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '31GB ValuePlus Plan 11260',
                'amount' => 11260.0,
                'service_id' => 8,
                'code' => 205,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Essential Plan 15350',
                'amount' => 15350.0,
                'service_id' => 8,
                'code' => 206,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '30Gb BumpaValue Plan 15350',
                'amount' => 15350.0,
                'service_id' => 8,
                'code' => 207,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Premium Plan 20270',
                'amount' => 20270.0,
                'service_id' => 8,
                'code' => 208,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '50GB Anytime Plan 36850',
                'amount' => 36850.0,
                'service_id' => 8,
                'code' => 210,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '60GB BumpaValue Plan 30700',
                'amount' => 30700.0,
                'service_id' => 8,
                'code' => 209,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '80Gb Bumpa Value Plan 40950',
                'amount' => 40950.0,
                'service_id' => 8,
                'code' => 211,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '100GB Anytime Plan 71650',
                'amount' => 71650.0,
                'service_id' => 8,
                'code' => 212,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '200GB Anytime Plan 138200',
                'amount' => 138200.0,
                'service_id' => 8,
                'code' => 213,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Platinum Plan 24000',
                'amount' => 24000.0,
                'service_id' => 8,
                'code' => 214,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1GB Day Use + 500MB night use 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 215,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5Gb with 50% day + night use 3070',
                'amount' => 3070.0,
                'service_id' => 8,
                'code' => 216,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '7GB with 50% day + Night use 4090',
                'amount' => 4090.0,
                'service_id' => 8,
                'code' => 217,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '11Gb with 50% day + night use 5110',
                'amount' => 5110.0,
                'service_id' => 8,
                'code' => 218,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '15GB with 50% day + night use 6140',
                'amount' => 6140.0,
                'service_id' => 8,
                'code' => 219,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '21GB with 50% day + night use 8190',
                'amount' => 8190.0,
                'service_id' => 8,
                'code' => 220,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '31GB with 50% day + night use 11260',
                'amount' => 11260.0,
                'service_id' => 8,
                'code' => 221,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1GB FlexiDaily 350',
                'amount' => 350.0,
                'service_id' => 8,
                'code' => 222,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1.5GB Value Plus 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 223,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB FlexiWeekly 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 224,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB Midnite 1020',
                'amount' => 1020.0,
                'service_id' => 8,
                'code' => 225,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB Midnite 1530',
                'amount' => 1530.0,
                'service_id' => 8,
                'code' => 226,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB weekend ONLY 1530',
                'amount' => 1530.0,
                'service_id' => 8,
                'code' => 227,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5GB Value Plus 3070',
                'amount' => 3070.0,
                'service_id' => 8,
                'code' => 228,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '7GB ValuePlus 4090',
                'amount' => 4090.0,
                'service_id' => 8,
                'code' => 229,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '11GB ValuePlus 5110',
                'amount' => 5110.0,
                'service_id' => 8,
                'code' => 230,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '15GB Value Plus 6140',
                'amount' => 6140.0,
                'service_id' => 8,
                'code' => 231,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '21GB Value Plus 8190',
                'amount' => 8190.0,
                'service_id' => 8,
                'code' => 232,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '10GB Anytime 8190',
                'amount' => 8190.0,
                'service_id' => 8,
                'code' => 233,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'UnlimitedLite 10230',
                'amount' => 10230.0,
                'service_id' => 8,
                'code' => 234,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '31GB ValuePlus 11350',
                'amount' => 11350.0,
                'service_id' => 8,
                'code' => 235,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Essential 15350',
                'amount' => 15350.0,
                'service_id' => 8,
                'code' => 236,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '30GB BumbaValue 15350',
                'amount' => 15350.0,
                'service_id' => 8,
                'code' => 237,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '20GB Anytime 16380',
                'amount' => 16380.0,
                'service_id' => 8,
                'code' => 238,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Platinum 24000',
                'amount' => 24000.0,
                'service_id' => 8,
                'code' => 239,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '60GB Bumpa Value 30700',
                'amount' => 30700.0,
                'service_id' => 8,
                'code' => 240,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '50GB Anytime 36850',
                'amount' => 36850.0,
                'service_id' => 8,
                'code' => 241,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '80GB Bumpa Value 40950',
                'amount' => 40950.0,
                'service_id' => 8,
                'code' => 242,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5GB ValuePlus Plan 3070',
                'amount' => 71650.0,
                'service_id' => 8,
                'code' => 243,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '100GB Anytime 71650',
                'amount' => 138200.0,
                'service_id' => 8,
                'code' => 244,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1GB Flexi 300',
                'amount' => 300.0,
                'service_id' => 8,
                'code' => 245,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2.5GB Flexi 500',
                'amount' => 500.0,
                'service_id' => 8,
                'code' => 246,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1GB Flexi-Weekly 500',
                'amount' => 500.0,
                'service_id' => 8,
                'code' => 247,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1.5GB Bigga 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 248,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB Flexi-Weekly 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 249,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB Bigga 1200',
                'amount' => 1200.0,
                'service_id' => 8,
                'code' => 250,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '3GB Bigga 1500',
                'amount' => 1500.0,
                'service_id' => 8,
                'code' => 251,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '6GB Flexi-Weekly 1500',
                'amount' => 1500.0,
                'service_id' => 8,
                'code' => 252,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5GB Bigga 2000',
                'amount' => 2000.0,
                'service_id' => 8,
                'code' => 253,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '6.5GB Bigga 2500',
                'amount' => 2500.0,
                'service_id' => 8,
                'code' => 254,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '8GB Bigga 3000',
                'amount' => 3000.0,
                'service_id' => 8,
                'code' => 255,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '10GB Bigga 3500',
                'amount' => 3500.0,
                'service_id' => 8,
                'code' => 256,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '12GB Bigga 4000',
                'amount' => 4000.0,
                'service_id' => 8,
                'code' => 257,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '15GB Anytime 5000',
                'amount' => 5000.0,
                'service_id' => 8,
                'code' => 258,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '20GB Biga 6000',
                'amount' => 6000.0,
                'service_id' => 8,
                'code' => 259,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '15GB Anytime 8000',
                'amount' => 8000.0,
                'service_id' => 8,
                'code' => 260,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '30GB Bigga 8000',
                'amount' => 8000.0,
                'service_id' => 8,
                'code' => 261,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '40GB Bigga 10000',
                'amount' => 10000.0,
                'service_id' => 8,
                'code' => 262,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '60GB Bigga 13500',
                'amount' => 13500.0,
                'service_id' => 8,
                'code' => 263,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '75GB Bigga 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 264,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '50GB Bumpa-Value 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 265,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Unlimited-Essential 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 266,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '35GB Anytime 16000',
                'amount' => 16000.0,
                'service_id' => 8,
                'code' => 267,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '100GB Bigga 18000',
                'amount' => 18000.0,
                'service_id' => 8,
                'code' => 268,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '80GB Bumpa Value 30000',
                'amount' => 30000.0,
                'service_id' => 8,
                'code' => 269,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '90GB Anytime 36000',
                'amount' => 36000.0,
                'service_id' => 8,
                'code' => 270,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '100GB Bumpa Value 40000',
                'amount' => 40000.0,
                'service_id' => 8,
                'code' => 271,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '200GB Anytime 70000',
                'amount' => 70000.0,
                'service_id' => 8,
                'code' => 272,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '400GB Anytime 120000',
                'amount' => 120000.0,
                'service_id' => 8,
                'code' => 273,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited - Lite 10000',
                'amount' => 10000.0,
                'service_id' => 8,
                'code' => 274,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited-Lite 10000',
                'amount' => 10000.0,
                'service_id' => 8,
                'code' => 275,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => '6GB FlexiWeekly 1500',
                'amount' => 1500.0,
                'service_id' => 8,
                'code' => 278,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1GB FlexiDaily 300',
                'amount' => 300.0,
                'service_id' => 8,
                'code' => 279,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2.5GB FlexiDaily 500',
                'amount' => 500.0,
                'service_id' => 8,
                'code' => 280,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2GB FlexiWeekly 1000',
                'amount' => 1000.0,
                'service_id' => 8,
                'code' => 281,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'UnlimitedLite 10000',
                'amount' => 10000.0,
                'service_id' => 8,
                'code' => 282,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '50GB Bumpa Value 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 283,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Unlimited Essential 15000',
                'amount' => 15000.0,
                'service_id' => 8,
                'code' => 284,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'name' => '80GB Bumpa Value 30000',
                'amount' => 30000.0,
                'service_id' => 8,
                'code' => 285,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '100GB BumpaValue 40000',
                'amount' => 40000.0,
                'service_id' => 8,
                'code' => 286,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '15GB Anytime 8000',
                'amount' => 8000.0,
                'service_id' => 8,
                'code' => 287,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '35GB anytime 16000',
                'amount' => 16000.0,
                'service_id' => 8,
                'code' => 288,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '90GB Anytime 36000',
                'amount' => 36000.0,
                'service_id' => 8,
                'code' => 289,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '200GB Anytime 70000',
                'amount' => 70000.0,
                'service_id' => 8,
                'code' => 290,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '400GB Anytime 120000',
                'amount' => 120000.0,
                'service_id' => 8,
                'code' => 291,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '90GB Jumbo 20000',
                'amount' => 20000.0,
                'service_id' => 8,
                'code' => 292,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Freedom 3Mbps 20000',
                'amount' => 20000.0,
                'service_id' => 8,
                'code' => 293,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Freedom 6Mbps 24000',
                'amount' => 24000.0,
                'service_id' => 8,
                'code' => 294,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5GB ValuePlus Plan 3070',
                'amount' => 34000.0,
                'service_id' => 8,
                'code' => 295,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'BestEffort Freedom 36000',
                'amount' => 36000.0,
                'service_id' => 8,
                'code' => 296,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '200GB Jumbo 40000',
                'amount' => 40000.0,
                'service_id' => 8,
                'code' => 297,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '125GB 365 50000',
                'amount' => 50000.0,
                'service_id' => 8,
                'code' => 298,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '200GB 365 70000',
                'amount' => 70000.0,
                'service_id' => 8,
                'code' => 299,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '500GB 365 100000',
                'amount' => 100000.0,
                'service_id' => 8,
                'code' => 300,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1TB 365 120000',
                'amount' => 120000.0,
                'service_id' => 8,
                'code' => 301,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1TB 365 100000',
                'amount' => 100000.0,
                'service_id' => 8,
                'code' => 302,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '130GB Bigga 19800',
                'amount' => 19800.0,
                'service_id' => 8,
                'code' => 303,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Freedom 3Mbps 24000',
                'amount' => 24000.0,
                'service_id' => 8,
                'code' => 304,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'UnlimitedPremium 20000',
                'amount' => 20000.0,
                'service_id' => 8,
                'code' => 276,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1GB FlexiWeekly 500',
                'amount' => 500.0,
                'service_id' => 8,
                'code' => 277,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '500',
                'amount' => 500.0,
                'service_id' => 9,
                'code' => 78,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1000',
                'amount' => 1000.0,
                'service_id' => 9,
                'code' => 79,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '2000',
                'amount' => 2000.0,
                'service_id' => 9,
                'code' => 80,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '5000',
                'amount' => 5000.0,
                'service_id' => 9,
                'code' => 81,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '7000',
                'amount' => 7000.0,
                'service_id' => 9,
                'code' => 82,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => '1000',
                'amount' => 1000.0,
                'service_id' => 9,
                'code' => 83,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-75MB for 1 day',
                'amount' => 100.0,
                'service_id' => 5,
                'code' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-750MB for 14 days',
                'amount' => 500.0,
                'service_id' => 5,
                'code' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-1.5GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 5,
                'code' => 3,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-20GB for 30 days',
                'amount' => 5000.0,
                'service_id' => 5,
                'code' => 4,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-40GB for 30 days',
                'amount' => 10000.0,
                'service_id' => 5,
                'code' => 5,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-75GB2M for 60 days',
                'amount' => 20000.0,
                'service_id' => 5,
                'code' => 6,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-150GB3M for 90 days',
                'amount' => 50000.0,
                'service_id' => 5,
                'code' => 7,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-250GB3M for 90 days',
                'amount' => 75000.0,
                'service_id' => 5,
                'code' => 8,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-200MB for 2 days',
                'amount' => 200.0,
                'service_id' => 5,
                'code' => 9,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-350MB for 7 days',
                'amount' => 300.0,
                'service_id' => 5,
                'code' => 10,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-75GB for 30 days',
                'amount' => 15000.0,
                'service_id' => 5,
                'code' => 11,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-120GB2M for 60 days',
                'amount' => 30000.0,
                'service_id' => 5,
                'code' => 12,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-3GB1M for 30 days',
                'amount' => 1500.0,
                'service_id' => 5,
                'code' => 13,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-25GB1M for 30 days',
                'amount' => 6000.0,
                'service_id' => 5,
                'code' => 14,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-2GB1M for 30 days',
                'amount' => 1200.0,
                'service_id' => 5,
                'code' => 15,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-6GB for 7 days',
                'amount' => 1500.0,
                'service_id' => 5,
                'code' => 16,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-6GB1M for 30 days',
                'amount' => 2500.0,
                'service_id' => 5,
                'code' => 17,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-10GB1M for 30 days',
                'amount' => 3000.0,
                'service_id' => 5,
                'code' => 18,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-110GB1M for 30 days',
                'amount' => 20000.0,
                'service_id' => 5,
                'code' => 19,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-30GB1M for 30 days',
                'amount' => 13500.0,
                'service_id' => 5,
                'code' => 20,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-90GB1M for 30 days',
                'amount' => 40000.0,
                'service_id' => 5,
                'code' => 21,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-325GB6M for 180 days',
                'amount' => 100000.0,
                'service_id' => 5,
                'code' => 22,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-1T1Y for 1 Year',
                'amount' => 100000.0,
                'service_id' => 5,
                'code' => 23,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-1.5T1Y for 1 Year',
                'amount' => 450000.0,
                'service_id' => 5,
                'code' => 24,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-400GB1Y for 1 Year',
                'amount' => 120000.0,
                'service_id' => 5,
                'code' => 25,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-1GB1W for 7 days',
                'amount' => 500.0,
                'service_id' => 5,
                'code' => 26,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-2.5GB2D for 2 days',
                'amount' => 500.0,
                'service_id' => 5,
                'code' => 27,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-30GB2M for 60 days',
                'amount' => 8000.0,
                'service_id' => 5,
                'code' => 28,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-2GB2D for 1 day',
                'amount' => 500.0,
                'service_id' => 5,
                'code' => 29,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-12GB1M for 30 days',
                'amount' => 3500.0,
                'service_id' => 5,
                'code' => 30,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-4.5GB1M for 30 days',
                'amount' => 2000.0,
                'service_id' => 5,
                'code' => 31,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-5-750MB1W for 7 days',
                'amount' => 500.0,
                'service_id' => 5,
                'code' => 32,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GLO D-MFIN-6-150MB1D for 1 day',
                'amount' => 100.0,
                'service_id' => 24,
                'code' => 71,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'MTN D-MFIN-6-350MB2D for 2 days',
                'amount' => 200.0,
                'service_id' => 24,
                'code' => 72,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GLO D-MFIN-6-1.35GB2W for 14 days',
                'amount' => 500.0,
                'service_id' => 24,
                'code' => 73,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                
                
                
                [
                'name' => 'Glo D-MFIN-6-2.9GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 24,
                'code' => 74,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-5.8GB for 30 days',
                'amount' => 2000.0,
                'service_id' => 24,
                'code' => 75,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-7.7GB for 30 days',
                'amount' => 2500.0,
                'service_id' => 24,
                'code' => 76,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GLO D-MFIN-6-10GB for 30 days',
                'amount' => 3000.0,
                'service_id' => 24,
                'code' => 77,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'GLO D-MFIN-6-13.25GB for 30 days',
                'amount' => 4000.0,
                'service_id' => 24,
                'code' => 78,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-18.25GB for 30 days',
                'amount' => 5000.0,
                'service_id' => 24,
                'code' => 79,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-29.5GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 24,
                'code' => 80,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-50GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 24,
                'code' => 81,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-93GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 24,
                'code' => 82,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-119GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 24,
                'code' => 83,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-50MB1D for 1 day',
                'amount' => 50.0,
                'service_id' => 24,
                'code' => 84,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-138GB for 30 days',
                'amount' => 20000.0,
                'service_id' => 24,
                'code' => 85,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-250MB1N for 1 Night',
                'amount' => 25.0,
                'service_id' => 24,
                'code' => 86,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-500MB1N for 1 Night',
                'amount' => 50.0,
                'service_id' => 24,
                'code' => 87,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-1GB5N for 5 Nights',
                'amount' => 100.0,
                'service_id' => 24,
                'code' => 88,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-7GB for 7 days',
                'amount' => 1500.0,
                'service_id' => 24,
                'code' => 89,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-4.1GB for 30 days',
                'amount' => 1500.0,
                'service_id' => 24,
                'code' => 90,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-1.25GBSUN for 1 day (Sun)',
                'amount' => 200.0,
                'service_id' => 24,
                'code' => 91,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Glo D-MFIN-6-225GBOT for 30 days',
                'amount' => 30000.0,
                'service_id' => 24,
                'code' => 92,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-300GBOT for 30 days',
                'amount' => 36000.0,
                'service_id' => 24,
                'code' => 93,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-425GBOT for 90 days',
                'amount' => 50000.0,
                'service_id' => 24,
                'code' => 94,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-525GBOT for 120 days',
                'amount' => 60000.0,
                'service_id' => 24,
                'code' => 95,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Glo D-MFIN-6-675GBOT for 120 days',
                'amount' => 75000.0,
                'service_id' => 24,
                'code' => 96,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                 [
                'name' => 'Glo D-MFIN-6-1TBOT for 365 days',
                'amount' => 100000.0,
                'service_id' => 24,
                'code' => 97,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Etisalat D-MFIN-2-1.5GB for 30 days',
                'amount' => 1000.0,
                'service_id' => 6,
                'code' => 58,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Etisalat D-MFIN-2-2GB for 30 days',
                'amount' => 1200.0,
                'service_id' => 6,
                'code' => 59,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-4.5GB for 30 days',
                'amount' => 2000.0,
                'service_id' => 6,
                'code' => 60,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-40GB for 30 days',
                'amount' => 10000.0,
                'service_id' => 6,
                'code' => 61,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-165GB for 180 days',
                'amount' => 50000.0,
                'service_id' => 6,
                'code' => 62,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-365GB for 365 days',
                'amount' => 100000.0,
                'service_id' => 6,
                'code' => 63,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-11GB for 30 days',
                'amount' => 4000.0,
                'service_id' => 6,
                'code' => 64,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-25MB for 1 day',
                'amount' => 50.0,
                'service_id' => 6,
                'code' => 65,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Etisalat D-MFIN-2-100MB for 1 day',
                'amount' => 100.0,
                'service_id' => 6,
                'code' => 66,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-650MB for 1 day',
                'amount' => 200.0,
                'service_id' => 6,
                'code' => 67,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-15GB for 30 days',
                'amount' => 5000.0,
                'service_id' => 6,
                'code' => 68,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-100GB for 100 days',
                'amount' => 84992.0,
                'service_id' => 6,
                'code' => 69,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Etisalat D-MFIN-2-7GB for 7 days',
                'amount' => 1500.0,
                'service_id' => 6,
                'code' => 70,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                
                [
                'name' => 'Airtel D-MFIN-1-40MB for 1 day',
                'amount' => 50.0,
                'service_id' => 7,
                'code' => 33,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-100MB for 1 day',
                'amount' => 99.0,
                'service_id' => 7,
                'code' => 34,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-200GB for 3 days',
                'amount' => 199.0,
                'service_id' => 7,
                'code' => 35,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-350MB for 7 days',
                'amount' => 299.0,
                'service_id' => 7,
                'code' => 36,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-750MB for 14 days',
                'amount' => 499.0,
                'service_id' => 7,
                'code' => 37,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-1.5GB for 7 days',
                'amount' => 999.0,
                'service_id' => 7,
                'code' => 38,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-3GB for 30 days',
                'amount' => 1499.0,
                'service_id' => 7,
                'code' => 39,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-6GB for 30 days',
                'amount' => 2499.0,
                'service_id' => 7,
                'code' => 40,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-11GB for 30 days',
                'amount' => 3999.0,
                'service_id' => 7,
                'code' => 41,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                
                [
                'name' => 'Airtel D-MFIN-1-15GB for 30 days',
                'amount' => 4999.0,
                'service_id' => 7,
                'code' => 42,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-40GB for 30 days',
                'amount' => 9999.0,
                'service_id' => 7,
                'code' => 43,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-75GB for 30 days',
                'amount' => 14999.0,
                'service_id' => 7,
                'code' => 44,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-8GB for 30 days',
                'amount' => 2999.0,
                'service_id' => 7,
                'code' => 45,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-110GB for 30 days',
                'amount' => 19999.0,
                'service_id' => 7,
                'code' => 46,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-1GB1D for 1 day',
                'amount' => 299.0,
                'service_id' => 7,
                'code' => 47,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-2GB1D for 1 day',
                'amount' => 499.0,
                'service_id' => 7,
                'code' => 48,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                [
                'name' => 'Airtel D-MFIN-1-6GB1W for 7 days',
                'amount' => 1499.0,
                'service_id' => 7,
                'code' => 49,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                 [
                'name' => 'Airtel D-MFIN-1-2GB1M for 30 days',
                'amount' => 1199.0,
                'service_id' => 7,
                'code' => 50,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-4.5GB for 30 days',
                'amount' => 1999.0,
                'service_id' => 7,
                'code' => 51,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                
                
                
                [
                'name' => 'Airtel D-MFIN-1-25GB1M for 30 days',
                'amount' => 7999.0,
                'service_id' => 7,
                'code' => 52,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-200GB1M for 30 days',
                'amount' => 29999.0,
                'service_id' => 7,
                'code' => 53,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-280GB1M for 30 days',
                'amount' => 35999.0,
                'service_id' => 7,
                'code' => 54,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                
                
                
                
                [
                'name' => 'Airtel D-MFIN-1-400GB3M for 90 days',
                'amount' => 49999.0,
                'service_id' => 7,
                'code' => 55,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-500GB4M for 120 days',
                'amount' => 59999.0,
                'service_id' => 7,
                'code' => 56,
                'created_at' => now(),
                'updated_at' => now(),
                ],
                
                [
                'name' => 'Airtel D-MFIN-1-1TB1Y for 365 days',
                'amount' => 99999.0,
                'service_id' => 7,
                'code' => 57,
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
        Schema::dropIfExists('data_bundles');
    }
}
