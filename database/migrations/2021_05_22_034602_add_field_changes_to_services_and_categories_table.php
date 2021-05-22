<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldChangesToServicesAndCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("ALTER TABLE service_categories CHANGE description description text ;");
       DB::statement("ALTER TABLE services CHANGE description description text;");
    }   
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
