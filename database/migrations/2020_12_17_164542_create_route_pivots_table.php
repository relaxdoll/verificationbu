<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutePivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_pivots', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('order')->unsigned();
            $table->smallInteger('place_id')->unsigned();
            $table->smallInteger('route_id')->unsigned();
            $table->smallInteger('type_id')->unsigned()->nullable();
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
        Schema::dropIfExists('route_pivots');
    }
}
