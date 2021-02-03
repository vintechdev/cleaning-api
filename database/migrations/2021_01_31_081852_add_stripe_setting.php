<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB as DBAlias;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddStripeSetting
 */
class AddStripeSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DBAlias::statement('DELETE FROM settings');
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->index(['type', 'key'], 'settings_type_key_idx');
        });

        DBAlias::table('settings')->insert([
            [
                'type' => 'payment',
                'key' => 'stripe_service_fee_percentage',
                'value' => 1
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
