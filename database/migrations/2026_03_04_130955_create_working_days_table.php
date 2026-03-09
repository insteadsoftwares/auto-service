<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_garage_working_days', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('garage_id')->nullable(); 
			$table->foreign('garage_id')->references('id')->on('aus_garages');
			$table->unsignedTinyInteger('day_of_week');
            $table->boolean('is_open')->default(true);
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
        Schema::dropIfExists('working_days');
    }
}
