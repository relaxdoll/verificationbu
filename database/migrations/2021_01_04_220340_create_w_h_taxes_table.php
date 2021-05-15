<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWHTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_h_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->json('line_items');
            $table->string('bill_id')->unique();
            $table->string('vendor_id');
            $table->string('vendor_name');
            $table->decimal('total', 12,2);
            $table->date('date');
            $table->date('due_date');
            $table->string('bill_number');
            $table->string('tax_type')->nullable();
            $table->json('tax_detail')->nullable();
            $table->date('tax_date')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('w_h_taxes');
    }
}
