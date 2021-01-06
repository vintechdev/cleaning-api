<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterBookingStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE booking_status MODIFY COLUMN status enum('pending','approved','arrived','completed','cancelled','rejected','payment_waiting','accepted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
        DB::statement("UPDATE booking_status set status='accepted', description='accepted' where id=2");
        DB::statement("ALTER TABLE booking_status MODIFY COLUMN status enum('pending','arrived','completed','cancelled','rejected','accepted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
        DB::statement('DELETE FROM booking_status where id=7');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE booking_status MODIFY COLUMN status enum('pending','approved','arrived','completed','cancelled','rejected','payment_waiting','accepted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
        DB::statement("UPDATE booking_status set status='approved', description='approved' where id=2");
        DB::statement("ALTER TABLE booking_status MODIFY COLUMN status enum('pending','approved','arrived','completed','cancelled','rejected','payment_waiting') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
    }
}
