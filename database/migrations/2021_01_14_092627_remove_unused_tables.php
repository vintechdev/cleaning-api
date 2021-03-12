<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cron_jobs');
        Schema::dropIfExists('provider_portfolios');
        Schema::dropIfExists('provider_skills');
        Schema::dropIfExists('reported_incidents');
        Schema::dropIfExists('booking_recurring_patterns');
        Schema::dropIfExists('city');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('customer_metadata');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('hacking_activities');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_acivities');
        Schema::dropIfExists('payment_activity_logs');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('payment_option');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('promocodes');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_items');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_ticket_histories');
        Schema::dropIfExists('once_booking_alternate_dates');
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
