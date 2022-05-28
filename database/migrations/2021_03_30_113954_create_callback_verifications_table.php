<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateCallbackVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callback_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username');
            $table->string('password');
            $table->enum('mode',['live','sandbox']);
            $table->timestamps();
        });

        DB::table('callback_verifications')->insert([
            'id' => Str::uuid(),
            'username' => 'VFD-Auth',
            'password' => Hash::make('vfd@Sde9'),
            'mode' => 'live'
        ]);

        DB::table('callback_verifications')->insert([
            'id' => Str::uuid(),
            'username' => 'VFD-Auth-sandbox',
            'password' => Hash::make('vfd_sandbox2'),
            'mode' => 'sandbox'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('callback_verifications');
    }
}
