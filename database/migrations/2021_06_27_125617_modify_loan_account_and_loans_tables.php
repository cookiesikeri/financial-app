<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLoanAccountAndLoansTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_kycs', function (Blueprint $table) {
           $table->boolean('is_completed')->after('monthly_income_id')->default(false);
        });

        //loan table alteration
        if (in_array('loans_loan_account_id_foreign', $this->listTableForeignKeys('loans'))) {
            Schema::table('loans', function (Blueprint $table) {
                $table->dropForeign('loans_loan_account_id_foreign');
            });
        }
        if (Schema::hasColumn('loans', 'loan_account_id'))
        {
            Schema::table('loans', function (Blueprint $table)
            {
                $table->dropColumn('loan_account_id');
            });
        }
        //payment cards table alteration
        if (in_array('payment_cards_loan_account_id_foreign', $this->listTableForeignKeys('payment_cards'))) {
            Schema::table('payment_cards', function (Blueprint $table) {
                $table->dropForeign('payment_cards_loan_account_id_foreign');
            });
        }
        if (Schema::hasColumn('payment_cards', 'loan_account_id'))
        {
            Schema::table('payment_cards', function (Blueprint $table)
            {
                $table->dropColumn('loan_account_id');
            });
        }
        //add user id to loans
        Schema::table('loans', function (Blueprint $table) {
            $table->uuid('user_id')->after('id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_kycs', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('loans_user_id_foreign');
            $table->dropColumn('user_id');
            //$table->uuid('loan_account_id');

            //$table->foreign('loan_account_id')->references('id')->on('loan_kycs')->onDelete('cascade');
        });
    }

    /**
     * @param $table
     * @return array
     */
    public function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }
}
