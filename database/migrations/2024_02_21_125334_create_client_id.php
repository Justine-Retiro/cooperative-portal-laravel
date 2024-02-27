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
            $table->unsignedBigInteger('client_id')->nullable()->after('account_number_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_application', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('account_number_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }
};
