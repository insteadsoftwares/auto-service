<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_roles', function (Blueprint $table) {
            $table->id();
			$table->string('name');
            $table->timestamps();
        });

		DB::table('aus_roles')->insert([
            ['name' => 'super_admin'],
            ['name' => 'admin'],
            ['name' => 'technician'],
            ['name' => 'client'],
        ]);
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
