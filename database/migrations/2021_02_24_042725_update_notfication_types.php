<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotficationTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE notification_settings MODIFY COLUMN 
    notification_type enum('transactional', 'tasks update', 'booking_updates', 'new_booking_request_provider', 'task_reminder', 'help_information' , 'updates_and_news_letters')");

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
