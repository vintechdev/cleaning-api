<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserreviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userreviews', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('user_review_for')->unsigned()->nullable();
            $table->integer('user_review_by')->unsigned()->nullable();
            $table->integer('booking_id')->unsigned()->nullable();
            $table->enum('rating', ['one', 'two', 'three', 'four', 'five'])->nullable();
            $table->char('comments', 191)->nullable();
            $table->tinyInteger('published')->nullable();
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
        Schema::dropIfExists('userreviews');
    }
}
