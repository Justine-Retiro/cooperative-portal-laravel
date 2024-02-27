<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_application_payment', function (Blueprint $table) {
            $table->bigIncrements('id')->before('payment_id');
            $table->string('remarks', 20)->after('loan_application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_application_payment', function (Blueprint $table) {
            //
        });
    }
};
