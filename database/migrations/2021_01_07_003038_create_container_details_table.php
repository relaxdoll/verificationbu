<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_details', function (Blueprint $table) {
            $table->id();
            $table->string('container_no')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('imo')->nullable();
            $table->string('vessel_type')->nullable();
            $table->string('grt')->nullable();
            $table->mediumInteger('dwt')->unsigned()->nullable();
            $table->mediumInteger('container')->unsigned()->nullable();
            $table->decimal('length', 9, 2)->nullable();
            $table->decimal('breadth', 9, 2)->nullable();
            $table->decimal('draught', 9, 2)->nullable();
            $table->mediumInteger('engine_power')->nullable();
            $table->decimal('speed', 9, 2)->nullable();
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
        Schema::dropIfExists('container_details');
    }
}
