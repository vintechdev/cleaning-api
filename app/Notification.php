<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\UserNotification;

class Notification extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'notification_settings';
    use SoftDeletes;
    protected $fillable = ['id'];

    /**
     * allow_* = 0 -> it is compulsory for user
     * allow_* = 1 -> user will have choice to enable or disable
     * allow_* = 2  -> will not be enabled for user (and will not appear in setting list)
     **/

    /**
     * default_* = 0 -> default will be un checked
     * default_* = 1 -> default will be checked
     **/

    /**
     * display_provider = 1 -> notification type will be available on provider screen
     * display_user = 1 -> notification type will be available on user screen
     **/

    const TRANSACTIONAL=  'transactional';
    const BOOKING_UPDATES=  'booking_updates';
    const REQUEST_TO_PROVIDER=  'new_booking_request_provider';
    const TASK_REMINDER = 'task_reminder';
    const HELP = "help_information";
    const UPDATES = "updates_and_news_letters";

    public function userNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserNotification::class,'notification_id','id');
    }
}
