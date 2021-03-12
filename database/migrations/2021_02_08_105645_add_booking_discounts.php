<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_discounts', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('booking_id')->unsigned()->nullable(false);
            $table->bigInteger('discounts_id')->unsigned()->nullable(false);
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('discounts_id')->references('id')->on('discounts')->onDelete('cascade');

            $table->index(['booking_id'], 'booking_discounts_booking_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_discounts');
    }
}
