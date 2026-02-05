<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedToAppointmentsStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE aus_appointments 
            MODIFY status ENUM('pending', 'confirmed', 'completed', 'cancelled')
            NOT NULL
            DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments_status_enum', function (Blueprint $table) {
            //
        });
    }
}
