<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePVSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_v_s', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->smallInteger('pvNumber');
			$table->date('date');
			$table->date('dueDate');
			$table->decimal('amount', 12,2)->unsigned();
			$table->date('planDate')->nullable();
			$table->boolean('paid')->default(0);
			$table->date('paidDate')->nullable();
			$table->smallInteger('category_id')->unsigned();
			$table->smallInteger('vendor_id')->unsigned();
			$table->smallInteger('company_id')->unsigned();
			$table->smallInteger('cheque_id')->unsigned();
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
        Schema::dropIfExists('p_v_s');
    }
}
