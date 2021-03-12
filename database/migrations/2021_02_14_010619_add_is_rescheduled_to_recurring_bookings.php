<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRescheduledToRecurringBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurring_bookings', function (Blueprint $table) {
            $table->boolean('is_rescheduled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurring_bookings', function (Blueprint $table) {
            $table->dropColumn('is_rescheduled');
        });
    }
}
