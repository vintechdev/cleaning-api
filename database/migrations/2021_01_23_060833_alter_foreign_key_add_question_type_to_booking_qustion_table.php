<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class AlterForeignKeyAddQuestionTypeToBookingQustionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE booking_questions ADD COLUMN question_type VARCHAR(200);");
        DB::statement("ALTER TABLE booking_questions ADD COLUMN question_title VARCHAR(200);");
        DB::statement("ALTER TABLE booking_questions DROP foreign key services_question;");
        DB::statement("ALTER TABLE `booking_questions` DROP INDEX `services_question`;");
       
        DB::statement("ALTER TABLE booking_questions DROP service_question_id;");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      //  DB::statement("ALTER TABLE booking_questions DROP COLUMN question_type;");
      //  DB::statement("ALTER TABLE booking_questions ADD FOREIGN KEY service_question_id;");
    }
}
