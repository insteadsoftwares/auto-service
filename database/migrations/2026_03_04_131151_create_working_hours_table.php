<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_garage_working_hours', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('garage_id')->nullable(); 
			$table->foreign('garage_id')->references('id')->on('aus_garages');
			$table->unsignedBigInteger('working_day_id'); 
			$table->foreign('working_day_id')->references('id')->on('aus_garage_working_days');
            $table->time('start_time');
            $table->time('end_time');
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
        Schema::dropIfExists('working_hours');
    }
}
