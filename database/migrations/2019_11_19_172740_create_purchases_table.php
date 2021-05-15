<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->smallInteger('fleet_id')->unsigned();
			$table->smallInteger('vendor_id')->unsigned();
			$table->smallInteger('brand_id')->unsigned();
			$table->date('date');
			$table->decimal('price',12,2);
			$table->smallInteger('treadDepth')->unsigned();
			$table->smallInteger('type_id')->unsigned()->nullable();
            $table->smallInteger('inventory_type_id')->unsigned()->nullable();
			$table->smallinteger('amount');
			$table->smallInteger('user_id')->default(0);
            $table->string('purchase_order_number')->unique();
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
        Schema::dropIfExists('purchases');
    }
}
