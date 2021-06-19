<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTypeForQuestionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        
        DB::statement("ALTER TABLE `service_questions` CHANGE `question_type` `question_type` ENUM('text','radio','number','date','datetime-local','email','range','tel','time','url','week','checkbox','textarea','select') NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //


        DB::statement("ALTER TABLE `service_questions` CHANGE `question_type` `question_type` ENUM('text','radio','checkbox','textarea','select', 'number') NOT NULL;");
    }
}
