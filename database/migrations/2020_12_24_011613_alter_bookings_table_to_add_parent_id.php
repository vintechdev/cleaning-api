<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingsTableToAddParentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('parent_booking_id')->unsigned()->nullable(true);
            $table->foreign('parent_booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->index(['parent_booking_id', 'user_id'], 'bookings_parent_booking_user_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('parent_booking_id');
        });
    }
}
