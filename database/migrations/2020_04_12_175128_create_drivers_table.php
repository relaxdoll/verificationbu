<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('phone')->nullable();
            $table->string('lineId')->nullable();
            $table->integer('fleet_id');
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_vehicle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('driver_id')->unsigned()->nullable();
            $table->unsignedBigInteger('vehicle_id')->unsigned()->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('driver_tail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('driver_id')->unsigned()->nullable();
            $table->unsignedBigInteger('tail_id')->unsigned()->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('cascade');
            $table->foreign('tail_id')->references('id')->on('vehicles')
                ->onDelete('cascade');
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
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('driver');
        Schema::dropIfExists('drivers');
    }
}
