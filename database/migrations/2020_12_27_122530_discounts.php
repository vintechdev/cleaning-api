<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Discounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('discounts', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('category_id')->unsigned()->nullable(true);
            $table->integer('plan_id')->unsigned()->nullable(true);
            $table->enum('discount_type', ['flat', 'percentage']);
            $table->double('discount', 8, 2);
            $table->foreign('category_id')->references('id')->on('service_categories')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert some stuff
    DB::table('discounts')->insert(array(
        array(
            'category_id' => 1,
            'discount_type' => 'flat',
            'discount'=>25
        ),
        array(
            'plan_id' => 2,
            'discount_type' => 'percentage',
            'discount'=>30
        ),
        array(
            'plan_id' => 4,
            'discount_type' => 'percentage',
            'discount'=>50
        ))
    );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('discounts');
    }
}
