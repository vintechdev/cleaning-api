<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('stripe_user_metadata', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable(true)->change();
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
        Schema::table('stripe_user_metadata', function (Blueprint $table) {
            $table->dropColumn('stripe_connect_account_id');
            $table->dropColumn('stripe_connect_account_verified');
            $table->string('stripe_customer_id')->nullable(false)->change();
        });
    }
}
