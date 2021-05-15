<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_inventory_details', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('quantity_used')->unsigned();
            $table->smallInteger('maintenance_detail_id')->unsigned();
            $table->smallInteger('inventory_id')->unsigned();
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
        Schema::dropIfExists('maintenance_inventory_details');
    }
}
