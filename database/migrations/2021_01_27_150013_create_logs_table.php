<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
      
        DB::statement("CREATE TABLE IF NOT EXISTS `notification_logs` (
           `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `event_type` varchar(255) NOT NULL,
            `user_id` int(10) UNSIGNED NOT NULL,
            `booking_id` int(10) UNSIGNED NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `booking_id` (`booking_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

         // DB::statement("ALTER TABLE `notification_logs` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

          //email logs
         
          DB::statement("CREATE TABLE IF NOT EXISTS `email_notification_logs` (
            `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `notification_log_id` int(10) UNSIGNED NOT NULL,
            `message` blob,
            `subject` varchar(255) DEFAULT NULL,
            `status` enum('failed','sent') NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `notification_log_id` (`notification_log_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
          ");

            //push notification
           
            DB::statement("CREATE TABLE IF NOT EXISTS `push_notification_logs` (
                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `notification_log_id` int(10) UNSIGNED NOT NULL,
                `message` blob,
                `subject` varchar(255) DEFAULT NULL,
                `status` enum('failed','sent') NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `notification_log_id` (`notification_log_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
    
              //sms table
            
              DB::statement("CREATE TABLE IF NOT EXISTS `sms_notification_logs` (
                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `notification_log_id` int(10) UNSIGNED NOT NULL,
                `message` blob,
                `subject` varchar(255) DEFAULT NULL,
                `status` enum('failed','sent') NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `notification_log_id` (`notification_log_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TABLE `notification_logs`;");
        DB::statement("DROP TABLE `email_notification_logs`;");
        DB::statement("DROP TABLE `push_notification_logs`;");
        DB::statement("DROP TABLE `sms_notification_logs`;");
    }
}
