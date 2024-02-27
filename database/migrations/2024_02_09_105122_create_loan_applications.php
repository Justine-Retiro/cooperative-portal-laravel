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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('loan_reference');
            $table->string('customer_name');
            $table->integer('age');
            $table->date('birth_date')->nullable();
            $table->string('date_employed')->nullable();
            $table->bigInteger('contact_num', 20)->nullable();
            $table->string('college')->nullable();
            $table->string('taxid_num');
            $table->string('loan_type')->nullable();
            $table->string('work_position')->nullable();
            $table->integer('retirement_year');
            $table->integer('time_pay');
            $table->date('application_date');
            $table->string('application_status');
            $table->decimal('financed_amount', 15, 2);
            $table->decimal('finance_charge', 15, 2)->nullable();
            $table->decimal('monthly_pay', 8, 2);
            $table->decimal('balance', 15, 2)->nullable();
            $table->longText('note')->nullable();
            $table->date('due_date')->nullable();
            $table->string('remarks', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_applications');
    }
};
