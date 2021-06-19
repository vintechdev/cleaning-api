<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagToOverridePriceOnServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('allow_price_override')->default(false)->nullable(false);
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE provider_service_maps MODIFY COLUMN amount double(8,2) DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->removeColumn('allow_price_override');
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE provider_service_maps MODIFY COLUMN amount double(8,2) NOT NULL');
    }
}
