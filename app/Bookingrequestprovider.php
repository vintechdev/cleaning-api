<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Booking;

/**
 * Class Bookingrequestprovider
 * @package App
 */
class Bookingrequestprovider extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    use SoftDeletes;

	protected $table = 'booking_request_providers';
    protected $fillable = ['id'];
    public $incrementing = false;
    public function users()
    {
        return $this->belongsTo(Customeruser::class,'provider_user_id','id');
    }

    public function bookings()
    {
        return $this->belongsTo(Booking::class,'booking_id','id');
    }
    /**
     * @return int
     */
    public function getProviderId(): int
    {
        return $this->provider_user_id;
    }

    /**
     * @param $status
     * @return $this
     * @throws \Exception
     */
    public function setStatus($status)
    {
        if (!in_array($status, [
            self::STATUS_ACCEPTED,
            self::STATUS_PENDING,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
        ])) {
            throw new \Exception('Invlaid value received for status');
        }

        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
