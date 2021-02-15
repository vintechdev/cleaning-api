<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRecurringTimestampInRecurringBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::raw('alter table recurring_bookings modify column recurred_timestamp timestamp default null');
        \Illuminate\Support\Facades\DB::raw('ALTER TABLE recurring_bookings ADD INDEX recurring_bookings_event_id_is_rescheduled (event_id, is_rescheduled)');
        \Illuminate\Support\Facades\DB::raw('ALTER TABLE recurring_bookings ADD INDEX recurring_bookings_is_rescheduled (is_rescheduled)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurring_bookings', function (Blueprint $table) {
            //
        });
    }
}
