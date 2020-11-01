<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomermetadatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_metadata', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('customer_metadata_uuid')->unique()->nullable();
            $table->integer('user_id')->unsigned();
            $table->enum('status', ['active', 'block', 'expire'])->nullable();
            $table->bigInteger('card_number')->nullable();
            $table->char('card_name', 191)->nullable();
            $table->enum('user_card_type', ['visa', 'master', 'international'])->nullable();
            $table->integer('card_cvv')->nullable();
            $table->integer('expiry_month')->nullable();
            $table->integer('expiry_year')->nullable();
            $table->date('user_card_expiry')->nullable();
            $table->integer('user_card_last_four')->nullable();
            $table->integer('user_stripe_customer_id')->nullable();
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
        Schema::dropIfExists('customer_metadata');
    }
}
