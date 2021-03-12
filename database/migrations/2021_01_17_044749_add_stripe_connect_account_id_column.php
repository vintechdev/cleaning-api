<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStripeConnectAccountIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE stripe_user_metadata MODIFY COLUMN stripe_customer_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL');
        Schema::table('stripe_user_metadata', function (Blueprint $table) {
            $table->string('stripe_connect_account_id')->nullable(true);
            $table->boolean('stripe_connect_account_verified')->nullable(false)->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE stripe_user_metadata MODIFY COLUMN stripe_customer_id VARCHAR(255) NOT NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ""');
        Schema::table('stripe_user_metadata', function (Blueprint $table) {
            $table->dropColumn('stripe_connect_account_id');
            $table->dropColumn('stripe_connect_account_verified');
        });
    }
}
