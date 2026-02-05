<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusGarageServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_garage_services', function (Blueprint $table) {
			$table->id(); 
			$table->unsignedBigInteger('garage_id');
			$table->foreign('garage_id')->references('id')->on('aus_garages');
			$table->unsignedBigInteger('service_id')->nullable();
			$table->foreign('service_id')->references('id')->on('aus_services');
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
        //
    }
}
