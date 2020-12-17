<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return Carbon
     */
    public function getStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i', $this->booking_date . ' ' . $this->booking_time);
    }

    /**
     * @return Carbon|null
     */
    public function getEndDate(): ?Carbon
    {
        if ($this->getPlanType() == Plan::ONCEOFF && $this->getFinalHours()) {
            $dateTime = clone $this->getStartDate();
            return self::getFinalBookingDateTime($dateTime, $this->getFinalHours());
        }

        return  null;
    }

    /**
     * @return int
     */
    public function getPlanType(): int
    {
        return $this->plan_type;
    }

    /**
     * @param int $planType
     * @return $this
     */
    public function setPlanType(int $planType)
    {
        if (!Plan::isValidPlan($planType)) {
            throw new \RuntimeException('Invalid plan type received');
        }
        $this->plan_type = $planType;
        return $this;
    }

    /**
     * @return float
     */
    public function getFinalHours(): float
    {
        return  $this->final_hours;
    }

    /**
     * @param int $finalHours
     * @return $this
     */
    public function setFinalHours(int $finalHours)
    {
        $this->final_hours = $finalHours;
        return $this;
    }

    /**
     * @param Carbon $dateTime
     * @param float $finalHours
     * @return Carbon
     */
    public static function getFinalBookingDateTime(Carbon $dateTime, float $finalHours): Carbon
    {
        $hours = floor($finalHours);
        $mins = round(($finalHours - $hours) * 60);
        $timeInMinutes = ($hours * 60) + $mins;
        return $dateTime->addMinutes($timeInMinutes);
    }

    /**
     * @param int $userId
     * @return Builder|null
     */
    public static function findByUserId(int $userId): ?Builder
    {
        return self::where(['user_id' => $userId]);
    }
}
