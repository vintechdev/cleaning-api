<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterNotificationLogsTableBookingIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE `notification_logs` CHANGE `booking_id` `booking_id` INT(10) UNSIGNED NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE `notification_logs` MODIFY CHANGE `booking_id` `booking_id` INT(10) NOT NULL;");
    }
}
