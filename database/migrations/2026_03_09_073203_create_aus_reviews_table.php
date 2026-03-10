<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus_reviews', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('client_id'); 
			$table->foreign('client_id')->references('id')->on('aus_users');
			$table->unsignedBigInteger('appointment_id'); 
			$table->foreign('appointment_id')->references('id')->on('aus_appointments');
            $table->integer('rating');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('aus_reviews');
    }
}
