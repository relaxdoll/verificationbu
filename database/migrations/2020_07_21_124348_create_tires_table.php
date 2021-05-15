<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tires', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->smallInteger('initial_tread_depth')->default(16);
            $table->smallInteger('tread_depth')->default(16);
            $table->smallInteger('side_wall_age')->nullable();
            $table->boolean('is_available')->default(0);
            $table->boolean('is_sold')->default(0);
            $table->smallInteger('reason_id')->nullable();
            $table->bigInteger('distance_travelled')->default(0);
            $table->decimal('price',12,2)->nullable();
            $table->smallInteger('fleet_id')->unsigned();
            $table->smallInteger('purchase_id')->unsigned()->default(0);
            $table->string('tire_type');
            $table->smallInteger('brand_id')->unsigned();
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
        Schema::dropIfExists('tires');
    }
}
