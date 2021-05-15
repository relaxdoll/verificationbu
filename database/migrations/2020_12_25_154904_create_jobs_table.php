<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->boolean('is_complete')->default(0);
            $table->boolean('is_active')->default(1);
            $table->boolean('allow_track')->nullable();
            $table->date('start_time')->nullable();
            $table->date('end_time')->nullable();
            $table->smallInteger('fleet_id')->unsigned();
            $table->smallInteger('job_status_id')->unsigned()->default(1);
            $table->smallInteger('customer_id')->unsigned();
            $table->smallInteger('route_id')->unsigned();
            $table->smallInteger('driver_id')->unsigned()->nullable();
            $table->smallInteger('head_id')->unsigned()->nullable();
            $table->smallInteger('tail_id')->unsigned()->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
