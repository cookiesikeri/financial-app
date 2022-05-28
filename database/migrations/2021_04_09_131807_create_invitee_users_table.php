<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitee_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 199);
            $table->string('password', 199);
            $table->string('email', 199)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitee_users');
    }
}
