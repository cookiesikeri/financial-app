<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassportAndIdCardTypeToKycTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->mediumText('passport_url')->after('address')->nullable();
            $table->unsignedBigInteger('id_card_type_id')->after('id_card_number')->nullable();
            $table->boolean('is_completed')->after('city')->default(false);

            $table->foreign('id_card_type_id')->references('id')->on('id_card_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->dropColumn('passport_url');
            $table->dropForeign('kycs_id_card_type_id_foreign');
            $table->dropColumn('id_card_type_id');
            $table->dropColumn('is_completed');
        });
    }
}
