<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToServiceQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_questions', function (Blueprint $table) {
            $table->boolean('mandatory')->default(false);
            $table->string('pattern', 50)->nullable();
            $table->string('min', 20)->nullable();
            $table->string('max', 20)->nullable();
        });

        DB::statement("ALTER TABLE `service_questions` CHANGE `question_type` `question_type` ENUM('text','radio','checkbox','textarea','select', 'number') NOT NULL;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_questions', function (Blueprint $table) {

            $table->dropColumn('mandatory');
            $table->dropColumn('pattern');
            $table->dropColumn('min');
            $table->dropColumn('max');
            //
        });

        DB::statement("ALTER TABLE `service_questions` CHANGE `question_type` `question_type` ENUM('text','radio','checkbox','textarea') NOT NULL;");
    }
}
