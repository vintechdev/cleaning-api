<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('notification_logs', 'user_type')) {
            Schema::table('notification_logs', function (Blueprint $table) {
                $table->enum('user_type', ['admin', 'user', 'provider'])->default('user');
            });
        }

        if (Schema::hasColumn('push_notification_logs', 'user_type')) {
            Schema::table('push_notification_logs', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
        }

        DB::statement("ALTER TABLE push_notification_logs CHANGE subject title varchar(100);");
        DB::statement("ALTER TABLE push_notification_logs MODIFY message varchar(500);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (!Schema::hasColumn('push_notification_logs', 'user_type')) {
            Schema::table('push_notification_logs', function (Blueprint $table) {
                $table->enum('user_type', ['admin', 'user', 'provider'])->default('user');
            });
        }

        if (Schema::hasColumn('notification_logs', 'user_type')) {
            Schema::table('notification_logs', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
        }

        DB::statement("ALTER TABLE push_notification_logs CHANGE  title  subject varchar(100);");
    }
}
