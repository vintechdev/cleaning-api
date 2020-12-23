<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_notes', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('booking_id')->unsigned()->nullable(false);
            $table->integer('user_id')->unsigned()->nullable(false);
            $table->integer('booking_status_id')->unsigned()->nullable(false);
            $table->text('notes')->default('');
            $table->timestamp('created')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('booking_status_id')->references('id')->on('booking_status');

            $table->index(['booking_id', 'user_id'], 'booking_notes_booking_user_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_notes');
    }
}
