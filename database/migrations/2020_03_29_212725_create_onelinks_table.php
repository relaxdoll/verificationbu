<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnelinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onelinks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vehicle_id')->nullable();
            $table->string('licenseplate');
            $table->integer('speed');
            $table->integer('odo_meter');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->string('io_name');
            $table->string('admin_level1_name');
            $table->string('admin_level2_name');
            $table->string('admin_level3_name');
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
        Schema::dropIfExists('onelinks');
    }
}
