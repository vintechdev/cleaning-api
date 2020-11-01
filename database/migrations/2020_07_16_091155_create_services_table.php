<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->char('name', 191)->nullable();
            $table->char('description', 191)->nullable();
            $table->string('image', 191)->nullable();
            $table->tinyInteger('is_default_service')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->enum('service_type', ['home', 'office', 'garden'])->nullable();
            $table->double('service_cost', 8, 2)->nullable();
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
        Schema::dropIfExists('services');
    }
}
