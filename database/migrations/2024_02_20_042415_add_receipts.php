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
        Schema::table('media', function (Blueprint $table) {
            $table->string('signature', 255)->after('id'); // Updated to specify varchar(255)
            $table->string('take_home_pay', 255)->after('signature'); // Updated to specify varchar(255)
            $table->unsignedBigInteger('loan_id'); // Added for foreign key reference
            
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
        Schema::table('media', function (Blueprint $table) {
            //
        });
    }
};
