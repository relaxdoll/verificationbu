<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate', 10,2);
            $table->boolean('is_active')->default(true);
            $table->date('start_date');
            $table->date('expired_date');
            $table->integer('vehicle_id');
            $table->integer('payment_type_id');
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
        Schema::dropIfExists('vehicle_payments');
    }
}
