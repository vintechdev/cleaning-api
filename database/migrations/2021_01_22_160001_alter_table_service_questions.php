<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class AlterTableServiceQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE service_questions DROP COLUMN question_type;");
        DB::statement("ALTER TABLE service_questions DROP COLUMN description;");
        DB::statement("ALTER TABLE `service_questions` CHANGE `question_values` `question_type` ENUM('text','radio','checkbox','textarea') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE `service_questions` CHANGE  `question_type` `question_values` ENUM('text','radio','checkbox','textarea') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE service_questions ADD COLUMN question_type varchar(200);");
        DB::statement("ALTER TABLE service_questions ADD COLUMN description  text;");
      
    }
}
