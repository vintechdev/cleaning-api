<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBookingStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_status', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->enum('status', ['pending', 'accepted', 'arrived', 'completed','cancelled', 'rejected'])->nullable();
            $table->char('description', 191)->nullable();
            $table->timestamps();   
            $table->softDeletes();
        });

        // Insert some stuff
    DB::table('booking_status')->insert(array(
        array(
            'status' => 'pending',
            'description' => 'pending',
        ),
        array(
            'status' => 'accepted',
            'description' => 'accepted',
        ),
        array(
            'status' => 'arrived',
            'description' => 'arrived',
        ),
        array(
            'status' => 'completed',
            'description' => 'completed',
        ),
        array(
            'status' => 'cancelled',
            'description' => 'cancelled',
        ),
        array(
            'status' => 'rejected',
            'description' => 'rejected',
        ),
        
        )
    );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_status');
    }
}
