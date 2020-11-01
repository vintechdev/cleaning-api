<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('provider_id')->unsigned()->nullable();
            $table->integer('booking_status_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_recurring');
            $table->char('parent_event_id', 191)->nullable();
            $table->dateTime('booking_datetime')->nullable();
            $table->integer('booking_postcode')->nullable();
            $table->enum('booking_provider_type', ['freelancer', 'agency'])->nullable();
            $table->enum('plan_type', ['just once', 'weekly', 'beweekly', 'monthly'])->nullable();
            $table->char('promocode', 191)->nullable();
            $table->double('completed_work_total_cost', 8, 2);
            $table->double('completed_work_service_fee', 8, 2);
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
        Schema::dropIfExists('bookings');
    }
}
