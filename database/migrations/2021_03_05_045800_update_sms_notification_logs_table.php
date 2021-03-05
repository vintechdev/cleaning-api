<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateSmsNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE sms_notification_logs CHANGE message message varchar(500);");

        if (Schema::hasColumn('sms_notification_logs', 'subject')) {
            Schema::table('sms_notification_logs', function (Blueprint $table) {
                $table->dropColumn('subject');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (!Schema::hasColumn('sms_notification_logs', 'user_type')) {
            Schema::table('sms_notification_logs', function (Blueprint $table) {
                $table->string('subject');
            });
        }

    }
}
