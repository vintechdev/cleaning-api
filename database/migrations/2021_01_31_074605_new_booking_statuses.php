<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class NewBookingStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_status', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        DB::statement("ALTER TABLE `booking_status` MODIFY COLUMN `status` enum('pending','arrived','completed','cancelled','rejected','accepted', 'pending_approval', 'approved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");

        DB::table('booking_status')->insert(array(
                [
                    'id' => 7,
                    'status' => 'pending_approval',
                    'description' => 'pending approval',
                ],
                [
                    'id' => 8,
                    'status' => 'approved',
                    'description' => 'approved',
                ],
            )
        );
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
