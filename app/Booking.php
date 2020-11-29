<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'bookings';
    use SoftDeletes;
    protected $fillable = ['id'];

    public function event()
    {
        return $this->hasOne(Event::class);
    }
}
