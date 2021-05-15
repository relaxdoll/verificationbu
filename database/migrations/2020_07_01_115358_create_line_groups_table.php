<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lineId')->nullable();
            $table->string('name');
            $table->boolean('isLinked')->default(false);
            $table->integer('fleet_id');
            $table->string('avatar');
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
        Schema::dropIfExists('line_groups');
    }
}
