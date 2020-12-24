<?php

namespace App;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookingstatus extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'booking_status';
    use SoftDeletes;

    const BOOKING_STATUS_PENDING = 1;
    const BOOKING_STATUS_ACCEPTED = 2;
    const BOOKING_STATUS_ARRIVED = 3;
    const BOOKING_STATUS_COMPLETED = 4;
    const BOOKING_STATUS_CANCELLED = 5;
    const BOOKING_STATUS_REJECTED = 6;

    protected $fillable = ['id'];

    private static $idNameMap = [];

    /**
     * @param int $status
     * @return bool
     */
    public static function isValidStatus(int $status): bool
    {
        return in_array($status, [
            self::BOOKING_STATUS_PENDING,
            self::BOOKING_STATUS_ACCEPTED,
            self::BOOKING_STATUS_ARRIVED,
            self::BOOKING_STATUS_COMPLETED,
            self::BOOKING_STATUS_CANCELLED,
            self::BOOKING_STATUS_REJECTED,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getStatusNameById($id)
    {
        if (!self::$idNameMap) {
            $statuses = Bookingstatus::all();
            foreach ($statuses as $status) {
                self::$idNameMap[$status->id] = $status->status;
            }
        }

        return self::$idNameMap[$id];
    }
}
