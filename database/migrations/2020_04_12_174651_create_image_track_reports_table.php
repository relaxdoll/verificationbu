<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageTrackReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_track_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('image_array');
            $table->string('note');
            $table->integer('customer_id');
            $table->integer('driver_id');
            $table->integer('report_id');
            $table->integer('vehicle_id');
            $table->integer('vehicle_id2')->nullable();
            $table->integer('job_id')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('image_track_reports');
    }
}
