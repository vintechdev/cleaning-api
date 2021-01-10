<?php

namespace App\Services\Bookings\Interfaces;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface BookingListInterface
 * @package App\Services\Bookings\Interfaces
 */
interface BookingListInterface
{
    /**
     * @return Collection
     */
    public function getAllBookings(): Collection;

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user);

    /**
     * @param bool $isProvider
     * @return $this
     */
    public function setIsProvider(bool $isProvider);

    /**
     * @param int|null $month
     * @return $this;
     */
    public function setMonth(int $month = null);

    /**
     * @param int|null $year
     * @return $this
     */
    public function setYear(int $year = null);

    /**
     * @return Carbon|null
     */
    public function getFrom(): ?Carbon;

    /**
     * @return Carbon|null
     */
    public function getTo(): ?Carbon;
}