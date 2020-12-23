<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookingstatus extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'booking_status';
    use SoftDeletes;

    const BOOKING_STATUS_PENDING = 1;
    const BOOKING_STATUS_APPROVED = 2;
    const BOOKING_STATUS_ARRIVED = 3;
    const BOOKING_STATUS_COMPLETED = 4;
    const BOOKING_STATUS_CANCELLED = 5;
    const BOOKING_STATUS_REJECTED = 6;

    protected $fillable = ['id'];
}
