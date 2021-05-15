<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('nameTH');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('lineId')->nullable();
            $table->string('lineName')->nullable();
            $table->smallInteger('image_track_drive_id')->nullable();
            $table->smallInteger('user_id')->default(0);
            $table->smallInteger('fleet_id');
            $table->boolean('isActive')->default(1);
            $table->smallInteger('customer_address_id')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
