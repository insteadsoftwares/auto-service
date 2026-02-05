<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_appointments', function (Blueprint $table) { 
			$table->id(); $table->unsignedBigInteger('client_id'); 
			$table->foreign('client_id')->references('id')->on('aus_users'); 
			$table->unsignedBigInteger('garage_id')->nullable(); 
			$table->foreign('garage_id')->references('id')->on('aus_garages'); 
			$table->unsignedBigInteger('service_id')->nullable(); 
			$table->foreign('service_id')->references('id')->on('aus_services'); 
			$table->unsignedBigInteger('vehicle_id')->nullable(); 
			$table->foreign('vehicle_id')->references('id')->on('aus_client_vehicles'); 
			$table->date('appointment_date');   
    		$table->time('appointment_time');
			$table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending'); 
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
