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
     * @return int
     */
    public function getMonth(): ?int
    {
        return $this->month;
    }

    /**
     * @param int|null $month
     * @return $this
     */
    public function setMonth(int $month = null)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     * @return $this
     */
    public function setYear(int $year = null)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getFrom(): ?Carbon
    {
        if (!$this->year && !$this->month) {
            return null;
        }
        return Carbon::createFromFormat('dmY', '01' . $this->month . $this->year);
    }

    /**
     * @return Carbon|null
     */
    public function getTo(): ?Carbon
    {
        $from = $this->getFrom();
        if (!$from) {
            return null;
        }

        $to = clone $from;
        return $to->addMonth();
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