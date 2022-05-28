<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupportIdColumnToCustomerValidation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_validations', function (Blueprint $table) {
            $table->uuid('support_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('customer_validations', 'support_id')){
            Schema::table('customer_validations', function (Blueprint $table) {
                $table->dropColumn('support_id');
            });
        }
    }
}
