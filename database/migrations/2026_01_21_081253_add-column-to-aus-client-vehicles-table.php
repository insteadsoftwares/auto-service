<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnToAusClientVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('aus_client_vehicles', function (Blueprint $table) {
            $table->text('description')->nullable()->after('year');
        });

		DB::statement('ALTER TABLE aus_client_vehicles MODIFY brand_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE aus_client_vehicles MODIFY modele_id BIGINT UNSIGNED NULL');
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
