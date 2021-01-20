<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingServicesAddIsInitiallyAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_services', function (Blueprint $table) {
            $table->boolean('is_initially_added')->nullable(false)->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropColumn('is_initially_added');
        });
    }
}
