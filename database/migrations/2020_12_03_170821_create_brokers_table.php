<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->dateTime('request_date');
            $table->dateTime('operation_date')->nullable();
            $table->string('job_number');
            $table->smallInteger('broker_container_detail_id');
            $table->smallInteger('customer_id');
            $table->smallInteger('broker_type_id');
            $table->string('booking_number');
            $table->string('port')->nullable();
            $table->string('vessel')->nullable();
            $table->string('agent')->nullable();
            $table->dateTime('cy_date');
            $table->smallInteger('pick_up_place_id');
            $table->dateTime('cut_off_date');
            $table->smallInteger('return_place_id');
            $table->smallInteger('broker_end_user_id');
            $table->time('time_load');
            $table->string('head_license')->nullable();
            $table->string('tail_license')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_telephone')->nullable();
            $table->decimal('job_sale', 12, 2);
            $table->decimal('job_handling_price', 12, 2);
            $table->decimal('job_shore_price', 12, 2);
            $table->string('inv_number')->nullable();
            $table->string('supplier_inv_number')->nullable();
            $table->smallInteger('supplier_id')->nullable();
            $table->decimal('supplier_transport_service_charge', 12, 2)->nullable();
            $table->decimal('supplier_shore_service_charge', 12, 2)->nullable();
            $table->decimal('supplier_handling_charge', 12, 2)->nullable();
            $table->string('handling_charge_inv_number')->nullable();
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
        Schema::dropIfExists('brokers');
    }
}
