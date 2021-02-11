<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterStatusNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE `push_notification_logs` CHANGE `status` `status` ENUM('read','unread') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;");

        DB::statement("ALTER TABLE `email_notification_logs` CHANGE `status` `status` ENUM('pending','failed','sent') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;");

        DB::statement("ALTER TABLE `sms_notification_logs` CHANGE `status` `status` ENUM('pending','failed','sent') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;");
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
