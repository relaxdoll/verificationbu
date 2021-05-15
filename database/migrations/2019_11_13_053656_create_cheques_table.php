<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheques', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('chequeNumber')->unique()->unsigned();
			$table->integer('pvNumber')->unique()->unsigned();
			$table->decimal('amount', 12,2)->unsigned();
			$table->date('date');
			$table->boolean('orBearer');
			$table->boolean('acPayeeOnly');
			$table->boolean('cash');
			$table->boolean('printed');
			$table->boolean('paid')->default(0);
			$table->smallInteger('pv_id')->unsigned();
			$table->smallInteger('vendor_id')->unsigned();
			$table->smallInteger('company_id')->unsigned();
			$table->smallInteger('user_id')->unsigned();
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
        Schema::dropIfExists('cheques');
    }
}
