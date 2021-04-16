<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Bookingactivitylog
 * @package App
 */
class Bookingactivitylog extends Model
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;

    const ACTION_STATUS_CHANGED = 'booking_status_changed';
    const ACTION_BOOKING_CREATED = 'booking_created';

    protected $table = 'booking_activity_logs';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}