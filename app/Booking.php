<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return Carbon
     */
    public function getStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i', $this->booking_date . ' ' . $this->booking_time);
    }
}
