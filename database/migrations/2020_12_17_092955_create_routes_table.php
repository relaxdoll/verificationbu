<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('distance', 9, 2)->nullable();
            $table->decimal('fuel_allowance', 6, 2)->nullable();
            $table->decimal('fuel_rate', 4, 2)->nullable();
            $table->decimal('fuel_save', 6, 2)->nullable();
            $table->decimal('toll_fee', 6, 2)->nullable();
            $table->decimal('pm_fee', 9, 2)->nullable();
            $table->decimal('other', 9, 2)->nullable();
            $table->smallInteger('fleet_id');
            $table->smallInteger('customer_id');
            $table->smallInteger('trip_rate_id')->nullable();
            $table->smallInteger('incentive_id')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('routes');
    }
}
