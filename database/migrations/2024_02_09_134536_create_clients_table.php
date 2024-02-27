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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('provincial_address')->nullable();
            $table->string('city_address')->nullable();
            $table->string('mailing_address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            $table->bigInteger('phone_number')->nullable();
            $table->string('tax_id_number');
            $table->date('date_employed')->nullable();
            $table->string('position')->nullable();
            $table->string('nature_of_work')->nullable();
            $table->string('account_status')->nullable();

            $table->decimal('amount_of_share', 15, 2)->nullable();
            $table->unsignedDecimal('balance', 15, 2)->default(0);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('clients');
    }
};
