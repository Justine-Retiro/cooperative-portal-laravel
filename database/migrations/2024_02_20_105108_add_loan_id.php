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
        Schema::table('loan_application_approvals', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id')->after('id'); // Correct way to add the column
            $table->foreign('loan_id')->references('id')->on('loan_applications'); // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_application_approvals', function (Blueprint $table) {
            //
        });
    }
};
