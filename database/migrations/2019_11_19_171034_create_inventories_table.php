<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('serial')->nullable();
            $table->boolean('is_available')->default(1);
            $table->boolean('is_sold')->default(0);
            $table->decimal('price', 12, 2)->nullable();
            $table->smallInteger('quantity')->unsigned();
            $table->smallInteger('current_quantity')->unsigned();
            $table->smallInteger('inventory_type_id')->unsigned();
            $table->smallInteger('brand_id')->unsigned();
            $table->smallInteger('purchase_id')->unsigned()->default(0);
            $table->smallInteger('fleet_id')->default(0);
            $table->bigInteger('distance_travelled')->default(0);
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
        Schema::dropIfExists('inventories');
    }
}
