<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProviderMetadataRemoveUnwantedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provider_metadata', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'first_name',
                'bank_account_number',
                'stripe_connected_account_id',
                'account_holder_name',
                'account_holder_type',
                'is_agency',
                'bank_account_name',
                'routing_number',
                'last_name',
                'email',
                'dob',
                'city',
                'line1',
                'line2',
                'postal_code',
                'state',
                'bank_bsb',
                'created_at',
                'updated_at',
                'skills',
                'deleted_at',
                'date',
                'onboardstatus',
                'billing_address',
                'person_id',
                'bstate',
                'bcity',
                'bpostcode',
            ]);
        });
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
