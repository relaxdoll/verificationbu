<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplaceTiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replace_tires', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->date('date');
			$table->smallInteger('vehicle_id')->unsigned();
			$table->smallInteger('fleet_id')->unsigned();
			$table->integer('mileage')->unsigned();
			$table->smallInteger('old_tire_id')->unsigned()->nullable();
			$table->smallInteger('old_tire_treadDepth')->unsigned()->nullable();
			$table->smallInteger('new_tire_id')->unsigned();
			$table->smallInteger('new_tire_treadDepth')->unsigned();
			$table->smallInteger('new_tire_total_mileage')->unsigned()->nullable();
			$table->smallInteger('new_tire_total_treadDepth')->unsigned()->nullable();
			$table->smallInteger('placement')->unsigned();
			$table->smallInteger('user_id')->unsigned();
			$table->smallInteger('reason_id')->unsigned();
			$table->smallInteger('replace_id')->unsigned()->nullable();
			$table->smallInteger('status_id')->unsigned()->default(0);
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
        Schema::dropIfExists('replace_tires');
    }
}
