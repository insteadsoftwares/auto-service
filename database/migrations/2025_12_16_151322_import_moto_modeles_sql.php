<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImportMotoModelesSql extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::table('aus_vehicle_brands')->insert([
            ['id' => 112, 'name' => 'YAMAHA']
        ]);

        $sqlPath = database_path('seeders/moto_modeles.sql');
        $sql = File::get($sqlPath);
        DB::unprepared($sql);
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
