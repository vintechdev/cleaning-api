<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB as DBAlias;

class RecurringBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_bookings', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('booking_id')->unsigned()->nullable(false);
            $table->integer('event_id')->unsigned()->nullable(false);
            $table->timestamp('recurred_timestamp')->nullable(false);
            $table->timestamp('created')->default(DBAlias::raw('CURRENT_TIMESTAMP'));
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unique(['event_id', 'recurred_timestamp'], 'event_recurring_bookings_recurred_time_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurring_bookings');
    }
}
