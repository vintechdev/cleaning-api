<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
// for cashier payment
use Laravel\Cashier\Billable;

class UserNotification extends Model
{
    use uuids;
    use HasApiTokens, Notifiable;
    use Billable;
    protected $table = 'user_notifications';
    use SoftDeletes;
    protected $fillable = ['id'];
    public $incrementing = false;
}
