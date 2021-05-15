<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTirePlacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tire_placements', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->smallInteger('placement')->unsigned();
            $table->smallInteger('start_tread_depth')->unsigned();
            $table->smallInteger('end_tread_depth')->unsigned()->nullable();
            $table->bigInteger('start_mileage')->unsigned()->nullable();
            $table->bigInteger('end_mileage')->unsigned()->nullable();
            $table->boolean('is_on_vehicle')->default(1);

            $table->boolean('is_spare')->default(0);
            $table->smallInteger('vehicle_id')->unsigned();
            $table->smallInteger('reason_id')->unsigned()->nullable();
            $table->smallInteger('checker_id')->unsigned()->nullable();
            $table->smallInteger('installer_id')->unsigned()->nullable();
            $table->smallInteger('tire_id')->unsigned();
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
        Schema::dropIfExists('tire_placements');
    }
}
