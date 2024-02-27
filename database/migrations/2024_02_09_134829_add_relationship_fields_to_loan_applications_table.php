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
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('account_number_id')->nullable();
            $table->foreign('account_number_id', 'account_number_fk_9484383')->references('id')->on('users');
            $table->unsignedBigInteger('take_action_by_id')->nullable();
            $table->foreign('take_action_by_id', 'take_action_by_fk_9484382')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            //
        });
    }
};
