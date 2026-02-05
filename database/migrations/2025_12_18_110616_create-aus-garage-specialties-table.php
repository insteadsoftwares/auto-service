<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusGarageSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('aus_garage_specialties', function (Blueprint $table) {
			$table->id(); 
			$table->unsignedBigInteger('garage_id');
			$table->foreign('garage_id')->references('id')->on('aus_garages');
			$table->unsignedBigInteger('vehicle_type_id')->nullable();
			$table->foreign('vehicle_type_id')->references('id')->on('aus_vehicle_types');
			$table->unsignedBigInteger('vehicle_brand_id')->nullable();
			$table->foreign('vehicle_brand_id')->references('id')->on('aus_vehicle_brands');
			$table->unsignedBigInteger('vehicle_modele_id')->nullable();
			$table->foreign('vehicle_modele_id')->references('id')->on('aus_vehicle_modeles');
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
