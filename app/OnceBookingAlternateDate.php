<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class OnceBookingAlternateDate extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'once_booking_alternate_dates';
    use SoftDeletes;
    protected $fillable = ['id'];
}
