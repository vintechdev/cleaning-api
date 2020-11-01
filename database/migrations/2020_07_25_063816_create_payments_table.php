<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('booking_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->enum('stripe_charge_status', ['pending', 'completed'])->nullable();
            $table->dateTime('charge_completion_datetime')->nullable();
            $table->char('payment_descriptor', 191)->nullable();
            $table->double('total_amount', 8, 2);
            $table->enum('payout_status', ['processing', 'sent', 'returned', 'cancelled'])->nullable();
            $table->date('payout_date')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
