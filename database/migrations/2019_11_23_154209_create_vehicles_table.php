<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('license')->unique();
            $table->bigInteger('mileage');
			$table->smallInteger('fleet_id')->unsigned();
            $table->smallInteger('vehicle_type_id')->unsigned();
			$table->boolean('is_active')->default(1);
            $table->boolean('is_repairing')->default(0);
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
        Schema::dropIfExists('vehicles');
    }
}
