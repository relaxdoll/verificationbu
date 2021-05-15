<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('image_tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('fleet_id');
            $table->integer('image_number')->nullable();
            $table->integer('type');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_image_track', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->unsigned()->nullable();
            $table->unsignedBigInteger('image_track_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onDelete('cascade');
            $table->foreign('image_track_id')->references('id')->on('image_tracks')
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
        Schema::dropIfExists('image_tracks');
    }
}
