<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusClientVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_client_vehicles', function (Blueprint $table) { 
			$table->id(); $table->unsignedBigInteger('client_id'); 
			$table->foreign('client_id')->references('id')->on('aus_users'); 
			$table->unsignedBigInteger('type_id')->nullable(); 
			$table->foreign('type_id')->references('id')->on('aus_vehicle_types'); 
			$table->unsignedBigInteger('brand_id')->nullable(); 
			$table->foreign('brand_id')->references('id')->on('aus_vehicle_brands'); 
			$table->unsignedBigInteger('modele_id')->nullable(); 
			$table->foreign('modele_id')->references('id')->on('aus_vehicle_modeles'); 
			$table->string('registration_number')->nullable();
			$table->integer('year')->nullable();
			$table->softDeletes(); 
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
