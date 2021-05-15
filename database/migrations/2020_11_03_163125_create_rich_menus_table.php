<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRichMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rich_menus', function (Blueprint $table) {
            $table->id();
            $table->string('size');
            $table->boolean('selected');
            $table->string('name');
            $table->string('chatBarText');
            $table->string('richMenuId')->nullable();
            $table->string('link')->nullable();
            $table->json('areas');
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
        Schema::dropIfExists('rich_menus');
    }
}
