<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Services\Bookings\Interfaces\BookingListInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AbstractBookingList
 * @package App\Services\Bookings
 */
abstract class AbstractBookingList implements BookingListInterface
{
    /** @var User */
    protected $user;

    /** @var bool */
    protected $isProvider = false;

    /** @var int */
    protected $month;

    /** @var int */
    protected $year;

    /** @var Carbon|null */
    protected $from;

    /** @var Carbon|null */
    private $to;

    /**
     * AbstractBookingList constructor.
     */
    public function __construct()
    {
        $this->month = Carbon::now()->format('m');
        $this->year = Carbon::now()->format('Y');
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    public function isProvider(): bool
    {
        return $this->isProvider;
    }

    /**
     * @param bool $isProvider
     * @return $this
     */
    public function setIsProvider(bool $isProvider)
    {
        $this->isProvider = $isProvider;
        return $this;
    }

    /**
     * @param Carbon|null $from
     * @return $this
     */
    public function setFrom(Carbon $from = null)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param Carbon|null $to
     * @return $this
     */
    public function setTo(Carbon $to = null)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getFrom(): ?Carbon
    {
        return $this->from ? : Carbon::now();
    }

    /**
     * @return Carbon|null
     */
    public function getTo(): ?Carbon
    {
        return $this->to ? : ((clone $this->getFrom())->addMonth());
    }

    /**
     * @return Builder
     */
    protected function getBookingQuery(): Builder
    {
        if($this->isProvider()){
            return $this->getProviderBookingQuery();
        }

        return $this->getUserBookingQuery();
    }

    /**
     * @return Builder
     */
    protected function getProviderBookingQuery(): Builder
    {
        return Booking::leftJoin('booking_request_providers','booking_request_providers.booking_id','=','bookings.id')
            ->where('booking_request_providers.provider_user_id',$this->getUser()->getId());
    }

    /**
     * @return Builder
     */
    protected function getUserBookingQuery(): Builder
    {
        return Booking::where('user_id',$this->getUser()->getId());
    }
}