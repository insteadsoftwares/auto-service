<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_notifications', function (Blueprint $table) { 
			$table->id(); 
			$table->unsignedBigInteger('appointment_id'); 
			$table->foreign('appointment_id')->references('id')->on('aus_appointments'); 
            $table->string('title');
            $table->string('message')->nullable();
			$table->boolean('is_read')->default(false);
			$table->unsignedBigInteger('update_user_id'); 
			$table->foreign('update_user_id')->references('id')->on('aus_users'); 
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
