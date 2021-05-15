<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refuels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('driver_id')->unsigned();
            $table->integer('vehicle_id')->unsigned();
            $table->integer('fleet_id')->unsigned();
            $table->integer('odometer')->unsigned();
            $table->decimal('quantity', 5,1)->unsigned();
            $table->integer('distance')->nullable()->unsigned();
            $table->integer('vehicle_id2')->nullable()->unsigned();
            $table->integer('job_id')->nullable()->unsigned();
            $table->integer('tank_id')->nullable()->unsigned();
            $table->boolean('is_checked')->default(false);
            $table->json('image_array');
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
        Schema::dropIfExists('refuels');
    }
}
