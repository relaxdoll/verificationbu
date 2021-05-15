<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_approvals', function (Blueprint $table) {
            $table->id();
            $table->dateTime('request_date')->default(NOW());
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('approve_date')->nullable();
            $table->dateTime('schedule_date')->nullable();
            $table->string('symptom')->nullable();
            $table->bigInteger('request_mileage')->unsigned();
            $table->bigInteger('start_mileage')->unsigned()->nullable();
            $table->bigInteger('end_mileage')->unsigned()->nullable();
            $table->smallInteger('vehicle_id')->unsigned();
            $table->smallInteger('requester_id')->unsigned();
            $table->smallInteger('approver_id')->unsigned()->nullable();
            $table->smallInteger('status_id')->unsigned()->default(1);
            $table->smallInteger('technician_id')->unsigned()->nullable();
            $table->smallInteger('checker_id')->unsigned()->nullable();
            $table->smallInteger('repaired_fleet_id')->unsigned()->nullable();
            $table->json('start_image_array')->nullable();
            $table->json('end_image_array')->nullable();
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
        Schema::dropIfExists('maintenance_approvals');
    }
}
