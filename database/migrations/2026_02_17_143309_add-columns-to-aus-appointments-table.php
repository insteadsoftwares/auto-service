<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAusAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::statement("ALTER TABLE aus_appointments MODIFY client_id BIGINT UNSIGNED NULL");
        Schema::table('aus_appointments', function (Blueprint $table) {
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
			$table->unsignedBigInteger('guest_vehicle_type_id')->nullable(); 
			$table->foreign('guest_vehicle_type_id')->references('id')->on('aus_vehicle_types'); 
			$table->unsignedBigInteger('guest_vehicle_brand_id')->nullable(); 
			$table->foreign('guest_vehicle_brand_id')->references('id')->on('aus_vehicle_brands'); 
			$table->unsignedBigInteger('guest_vehicle_model_id')->nullable(); 
			$table->foreign('guest_vehicle_model_id')->references('id')->on('aus_vehicle_modeles');
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
