<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicequestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_questions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->enum('question_type', ['home', 'office', 'room'])->nullable();
            $table->enum('question_values', ['text', 'radio', 'checkbox', 'list'])->nullable();
            $table->char('title', 191)->nullable();
            $table->char('question', 191)->nullable();
            $table->char('description', 191)->nullable();
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
        Schema::dropIfExists('service_questions');
    }
}
