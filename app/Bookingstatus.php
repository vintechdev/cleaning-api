<?php

namespace App;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bookingstatus
 * @package App
 */
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
    const BOOKING_STATUS_PENDING_APPROVAL = 7;
    const BOOKING_STATUS_APPROVED = 8;

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
            self::BOOKING_STATUS_PENDING_APPROVAL,
            self::BOOKING_STATUS_APPROVED,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getStatusNameById($id)
    {
        $statuses = self::getAllStatusNames();
        return $statuses[$id];
    }

    /**
     * @return array
     */
    public static function getAllStatusNames()
    {
        if (!self::$idNameMap) {
            $statuses = Bookingstatus::all();
            foreach ($statuses as $status) {
                self::$idNameMap[$status->id] = $status->status;
            }
        }

        return self::$idNameMap;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getStatusIdByName(string $name): int
    {
        $statuses = self::getAllStatusNames();
        $statuses = array_flip($statuses);
        return $statuses[$name];
    }
}
