<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultFieldsToNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            //
            $table->tinyInteger('default_email');
            $table->tinyInteger('default_sms');
            $table->tinyInteger('default_push');
            $table->boolean('display_user')->default(true);
            $table->boolean('display_provider')->default(true);
        });

        \Illuminate\Support\Facades\DB::statement("TRUNCATE user_notifications");
        \Illuminate\Support\Facades\DB::statement("TRUNCATE notification_settings");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            //
            $table->dropColumn('default_email');
            $table->dropColumn('default_sms');
            $table->dropColumn('default_push');
           // $table->dropColumn('display_user');
           // $table->dropColumn('display_provider');
        });
    }
}
