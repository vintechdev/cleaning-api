<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB as DBAlias;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGateways extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name')->nullable(false);
            $table->boolean('is_active')->nullable(false)->default(false);
            $table->timestamp('created')->default(DBAlias::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('stripe_user_metadata', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->integer('user_id')->unsigned()->nullable(false);
            $table->string('stripe_customer_id');
            $table->string('stripe_payment_method_id', 100)->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created')->default(DBAlias::raw('CURRENT_TIMESTAMP'));
            $table->index('user_id', 'user_id_idx');
        });

        DBAlias::table('payment_gateways')->insert(
            [
                'id' => 1,
                'name' => 'Stripe',
                'is_active' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
}
