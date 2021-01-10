<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

    public function users()
    {
        return $this->belongsTo(Customeruser::class,'user_id','id');
    }

    public function address()
    {
        return $this->hasOne(Bookingaddress::class);
    }
    public function bookingstatus()
    {
        return $this->belongsTo(Bookingstatus::class,'booking_status_id','id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_type','id');
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }

    public function bookingquestions()
    {
        return $this->hasMany(Bookingquestion::class,'booking_id','id');
    }

    /**
     * @return Event|null
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * @return Carbon
     */
    public function getStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->booking_date . ' ' . $this->booking_time);
    }

    /**
     * @param Carbon $startDateTime
     * @return Booking
     */
    public function setStartDateTime(Carbon $startDateTime): Booking
    {
        $this->booking_date = $startDateTime->format('Y-m-d');
        $this->booking_time = $startDateTime->format('H:i:s');
        return $this;
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

        return null;
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
     * @return Carbon|null
     */
    public function getFinalBookingDateTime(): ?Carbon
    {
        if ($this->isRecurring()) {
            return null;
        }

        return self::calculateFinalBookingDateTime($this->getStartDate(), $this->getFinalHours());
    }

    /**
     * @param Carbon $dateTime
     * @param float $finalHours
     * @return Carbon
     */
    public static function calculateFinalBookingDateTime(Carbon $dateTime, float $finalHours): Carbon
    {
        $hours = floor($finalHours);
        $mins = round(($finalHours - $hours) * 60);
        $timeInMinutes = ($hours * 60) + $mins;
        return $dateTime->addMinutes($timeInMinutes);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $userId
     * @return Builder|null
     */
    public static function findByUserId(int $userId): ?Builder
    {
        return self::where(['user_id' => $userId]);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->booking_status_id;
    }

    public function setStatus(int $status)
    {
        if (!Bookingstatus::isValidStatus($status)) {
            throw new \Exception('Invalid status id received');
        }
        $this->booking_status_id = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isRecurring(): bool
    {
        return !$this->isChildBooking() &&
            !is_null($this->getEvent()) &&
            $this->getPlanType() !== Plan::ONCEOFF;
    }

    /**
     * TODO: Get rid of this field as there are other ways to identify recurring booking.
     * @param bool $recurring
     * @return $this
     */
    public function setRecurring(bool $recurring)
    {
        $this->is_recurring = (int) $recurring;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostCode(): int
    {
        return $this->booking_postcode;
    }

    /**
     * @param int $postCode
     * @return $this
     */
    public function setPostCode(int $postCode): Booking
    {
        $this->booking_postcode = $postCode;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingNotes()
    {
        return $this->hasMany(BookingNote::class, 'booking_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingServices()
    {
        return $this->hasMany(Bookingservice::class, 'booking_id', 'id');
    }

    /**
     * @return Collection
     */
    public function getBookingServices(): collection
    {
        return $this->bookingServices;
    }
    public function getBookingServicesArr(): array
    {
        return $this->bookingServices->toArray();
    }
    public function getUserDetails(){
        return $this->users->toArray();
    }
    /**
     * @return Collection
     */
    public function getBookingNotes(): Collection
    {
        return $this->bookingNotes;
    }

    /**
     * @param array $bookingNotes
     * @return bool
     */
    public function saveBookingNotes(array $bookingNotes): bool
    {
        /** @var BookingNote $bookingNote */
        $this->bookingNotes()->saveMany($bookingNotes);
        return true;
    }

    /**
     * @return bool
     */
    public function isChildBooking(): bool
    {
        return !is_null($this->parent_booking_id);
    }

    /**
     * @return bool
     */
    public function isRecurredBooking(): bool
    {
        return $this->isChildBooking();
    }

    /**
     * @return Booking |null
     */
    public function getParentBooking(): ?Booking
    {
        if (!$this->parent_booking_id) {
            return null;
        }

        return Booking::find($this->parent_booking_id);
    }
}
