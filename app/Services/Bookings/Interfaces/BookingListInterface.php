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
     * @param Carbon|null $from
     * @return $this
     */
    public function setFrom(Carbon $from = null);

    /**
     * @param Carbon|null $to
     * @return $this
     */
    public function setTo(Carbon $to = null);

    /**
     * @return Carbon|null
     */
    public function getFrom(): ?Carbon;

    /**
     * @return Carbon|null
     */
    public function getTo(): ?Carbon;
}