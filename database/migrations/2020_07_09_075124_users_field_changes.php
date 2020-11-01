<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersFieldChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->uuid('uuid')->unique()->nullable();
            $table->char('first_name', 191);
            $table->char('last_name', 191);
            $table->date('date_of_birth')->nullable();
            $table->string('address', 191)->nullable();
            $table->bigInteger('mobile_number')->nullable();
            $table->char('abn', 191)->nullable();
            $table->char('description', 191)->nullable();
            $table->char('social_login', 191)->nullable();
            $table->tinyInteger('email_verify')->nullable();
            $table->time('reset_time')->nullable();
            $table->integer('loging_attemp')->nullable();
            $table->enum('status', ['active', 'deactive', 'fraud', 'block'])->nullable();
            $table->char('fcm_token', 191)->nullable();
            $table->string('profilepic', 191)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn('uuid');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('address');
            $table->dropColumn('mobile_number');
            $table->dropColumn('abn');
            $table->dropColumn('description');
            $table->dropColumn('social_login');
            $table->dropColumn('email_verify');
            $table->dropColumn('reset_time');
            $table->dropColumn('loging_attemp');
            $table->dropColumn('status');
            $table->dropColumn('fcm_token');
            $table->dropColumn('profilepic');
            $table->dropColumn('deleted_at ');
        }); 
    }
}
