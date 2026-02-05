<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAusUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aus_users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id');
            $table->foreign('role_id')->references('id')->on('aus_roles');
        });

		DB::table('aus_users')->insert(
            [
                [
					'role_id' => 1,
                    'first_name' => 'Karim ',
                    'last_name' => 'Ben Abdelaziz',
                    'email' => 'karim.banabdelaziz@gmail.com',
                    'password' => '$2y$10$CeAZ.Qn9L6VSwfJrCTBKCupgYSbipl471QRJYEr5qD9FszHjni4.e',
                    'phone_number' => '28172363',
                    'address' => '15, rue Amilcar, Rades',
                ],
            ]
        );
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
