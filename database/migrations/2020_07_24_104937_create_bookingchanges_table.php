<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_changes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('booking_id')->unsigned()->nullable();
            $table->tinyInteger('is_rescheduled')->nullable();
            $table->tinyInteger('is_cancelled')->nullable();
            $table->dateTime('booking_datetime')->nullable();
            $table->integer('number_of_hours')->nullable();
            $table->double('agreed_service_amount', 8, 2);
            $table->char('comments', 191)->nullable();
            $table->integer('changed_by_user')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_changes');
    }
}
