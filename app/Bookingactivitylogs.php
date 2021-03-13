<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Bookingactivitylogs extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'booking_activity_logs';
    use SoftDeletes;
    protected $fillable = ['id'];
}
