<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable(true)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created')->nullable(true);
        });

        Schema::create('recurring_patterns', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('event_id')->unsigned();
            $table->string('recurringpatternable_type')->nullable(false);
            $table->integer('recurringpatternable_id')->unsigned();
            $table->integer('separation_count')->default(1);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->index('event_id', 'event_id_idx');
        });

        Schema::create('daily_recurring_patterns', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('hour_of_day')->nullable(true);
        });

        Schema::create('weekly_recurring_patterns', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('day_of_week')->nullable(true);
        });

        Schema::create('monthly_recurring_patterns', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('day_of_month')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
