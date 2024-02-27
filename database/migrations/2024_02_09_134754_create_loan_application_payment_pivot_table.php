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
        Schema::create('loan_application_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id', 'payment_id_fk_9484410')->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedBigInteger('loan_application_id');
            $table->foreign('loan_application_id', 'loan_application_id_fk_9484410')->references('id')->on('loan_applications')->onDelete('cascade');
            $table->string('remarks', 20);
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
        Schema::dropIfExists('loan_application_payment_pivot');
    }
};
