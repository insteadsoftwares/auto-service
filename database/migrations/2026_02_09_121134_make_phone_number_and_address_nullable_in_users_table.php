<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePhoneNumberAndAddressNullableInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aus_users', function (Blueprint $table) {
			DB::statement("ALTER TABLE aus_users MODIFY phone_number VARCHAR(255) NULL");
        	DB::statement("ALTER TABLE aus_users MODIFY address VARCHAR(255) NULL");
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
