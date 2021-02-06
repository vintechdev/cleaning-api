<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\UserNotification;

class Notification extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'notification_settings';
    use SoftDeletes;
    protected $fillable = ['id'];



    public function usernotification()
    {
        return $this->hasMany(UserNotification::class,'notification_id','id');
    }
}
