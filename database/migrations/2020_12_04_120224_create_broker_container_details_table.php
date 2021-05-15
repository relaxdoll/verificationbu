<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerContainerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_container_details', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('size');
            $table->smallInteger('quantity')->default(1);
            $table->decimal('tare', 8, 2)->nullable();
            $table->string('container_number');
            $table->string('seal_number');
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
        Schema::dropIfExists('broker_container_details');
    }
}
