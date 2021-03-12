<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateUserNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE IF NOT EXISTS `user_notifications` (
            `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `user_id` int(10) UNSIGNED DEFAULT NULL,
            `notification_id` int(10) UNSIGNED DEFAULT NULL,
            `sms` tinyint(4) NOT NULL,
            `email` tinyint(4) NOT NULL,
            `push` tinyint(4) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `usernotifications_user_notification_uuid_unique` (`uuid`),
            KEY `user_notifications` (`user_id`),
            KEY `notification_id` (`notification_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notification');
    }
}
