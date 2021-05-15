<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_rates', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('route_id');
            $table->smallInteger('trip_rate_type_id');
            $table->decimal('10-11', 12, 5)->nullable();
            $table->decimal('11-12', 12, 5)->nullable();
            $table->decimal('12-13', 12, 5)->nullable();
            $table->decimal('13-14', 12, 5)->nullable();
            $table->decimal('14-15', 12, 5)->nullable();
            $table->decimal('15-16', 12, 5)->nullable();
            $table->decimal('16-17', 12, 5)->nullable();
            $table->decimal('17-18', 12, 5)->nullable();
            $table->decimal('18-19', 12, 5)->nullable();
            $table->decimal('19-20', 12, 5)->nullable();
            $table->decimal('20-21', 12, 5)->nullable();
            $table->decimal('21-22', 12, 5)->nullable();
            $table->decimal('22-23', 12, 5)->nullable();
            $table->decimal('23-24', 12, 5)->nullable();
            $table->decimal('24-25', 12, 5)->nullable();
            $table->decimal('25-26', 12, 5)->nullable();
            $table->decimal('26-27', 12, 5)->nullable();
            $table->decimal('27-28', 12, 5)->nullable();
            $table->decimal('28-29', 12, 5)->nullable();
            $table->decimal('29-30', 12, 5)->nullable();
            $table->decimal('diesel_adjust', 9, 5)->default(0);
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
        Schema::dropIfExists('trip_rates');
    }
}
