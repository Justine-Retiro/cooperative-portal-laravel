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
        Schema::table('transaction_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('account_number_id')->nullable();
            $table->foreign('account_number_id', 'account_number_fk_9484441')->references('id')->on('users');
            $table->unsignedBigInteger('loan_application_id')->nullable();
            $table->foreign('loan_application_id', 'loan_reference_fk_9484442')->references('id')->on('loan_applications');
            $table->unsignedBigInteger('currently_assigned_id')->nullable();
            $table->foreign('currently_assigned_id', 'currently_assigned_fk_9484447')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
