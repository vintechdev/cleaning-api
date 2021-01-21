<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class UpdateWorkingDaysInProviderWorkingHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE provider_working_hours set working_days='monday'");
       
        DB::statement("ALTER TABLE provider_working_hours MODIFY COLUMN working_days enum('monday','tuesday','wednesday','thursday','saturnday','sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_working_hours', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE provider_working_hours MODIFY COLUMN working_days varchar(126) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
        });
    }
}
