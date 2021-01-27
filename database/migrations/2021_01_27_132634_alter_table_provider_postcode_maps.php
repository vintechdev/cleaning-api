<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class AlterTableProviderPostcodeMaps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("DROP TABLE IF EXISTS `provider_postcode_maps`;");
        DB::statement("CREATE TABLE `provider_postcode_maps` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(36) NOT NULL,
            `provider_id` int(10) unsigned NOT NULL,
            `postcode_id` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uuid` (`uuid`),
            KEY `provider_id` (`provider_id`),
            KEY `postcode_id` (`postcode_id`),
            CONSTRAINT `provider_id` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("DROP TABLE `provider_postcode_maps`;");
    }
}
