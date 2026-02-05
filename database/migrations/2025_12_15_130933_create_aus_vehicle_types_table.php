<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusVehicleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_vehicle_types', function (Blueprint $table) {
            $table->id();
			$table->string('type');
            $table->timestamps();
        });

		$types = ['Car', 'Motorcycle',];

        foreach ($types as $type) {
            DB::table('aus_vehicle_types')->updateOrInsert(
                ['type' => $type]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aus_vehicle_types');
    }
}
