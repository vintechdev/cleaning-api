<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->enum('notification_type', ['transactional', 'tasks update', 'cleaner alerts', 'task recommendations'])->nullable();
            $table->char('notification_name', 191)->nullable();
            $table->text('notification_description')->nullable();
            $table->tinyInteger('allow_sms')->nullable();
            $table->tinyInteger('allow_email')->nullable();
            $table->tinyInteger('allow_push')->nullable();
            $table->timestamps();   
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
