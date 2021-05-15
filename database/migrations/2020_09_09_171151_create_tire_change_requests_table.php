<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTireChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tire_change_requests', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tire_placement_id')->unsigned();
            $table->smallInteger('reason_id')->unsigned();
            $table->smallInteger('driver_id')->unsigned();
            $table->smallInteger('technician_id')->unsigned()->nullable();
            $table->smallInteger('is_changed')->unsigned()->default(0);
            $table->smallInteger('is_rejected')->unsigned()->default(0);
            $table->string('link');
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
        Schema::dropIfExists('tire_change_requests');
    }
}
