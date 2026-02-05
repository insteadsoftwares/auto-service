<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImportCarModelesSql extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('aus_vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });

		Schema::create('aus_vehicle_modeles', function (Blueprint $table) {
            $table->id();
            $table->string('modele');
            $table->unsignedInteger('brand_id');
            $table->string('rappel_marque');
            $table->timestamps();
        });


        $sqlPath = database_path('seeders/car_modeles.sql');
        $sql = File::get($sqlPath);
        DB::unprepared($sql);

		

		Schema::table('aus_vehicle_modeles', function (Blueprint $table) {
            $table->unsignedInteger('type_id')->default(1);
            $table->tinyInteger('active')->default(1);
        });

		DB::table('aus_vehicle_brands')->insertUsing(
            ['name', 'created_at', 'updated_at'],
            DB::table('aus_vehicle_modeles')
              ->select('rappel_marque', DB::raw('NOW()'), DB::raw('NOW()'))
              ->distinct()
        );

        // Mettre à jour brand_id dans aus_vehicle_modeles
        $vehicleModeles = DB::table('aus_vehicle_modeles')->get();
        foreach ($vehicleModeles as $modele) {
            $brand = DB::table('aus_vehicle_brands')->where('name', $modele->rappel_marque)->first();
            if ($brand) {
                DB::table('aus_vehicle_modeles')
                    ->where('id', $modele->id)
                    ->update(['brand_id' => $brand->id]);
            }
        }

        // Supprimer la colonne rappel_marque
        Schema::table('aus_vehicle_modeles', function ($table) {
            $table->dropColumn('rappel_marque');
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
