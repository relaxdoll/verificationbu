<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTirePressureAndTreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tire_pressure_and_treads', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tire_id')->unsigned();
            $table->smallInteger('tire_placement_id')->unsigned();
            $table->smallInteger('tread_depth')->unsigned();
            $table->smallInteger('tire_pressure')->unsigned();
            $table->smallInteger('driver_id')->unsigned();
            $table->smallInteger('vehicle_id')->unsigned();
            $table->bigInteger('mileage')->unsigned();
            $table->smallInteger('is_updated')->unsigned();
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
        Schema::dropIfExists('tire_pressure_and_treads');
    }
}
