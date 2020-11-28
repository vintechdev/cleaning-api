<?php

namespace App\Dto;

use Carbon\Carbon;

/**
 * Class BookingEventDto
 * @package App\Dto
 */
class BookingEventDto
{
    /** @var string */
    private $startDate;

    /** @var string */
    private $startTime;

    /** @var int */
    private $planId;

    /**
     * @return string
     */
    public function getStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->startDate . ' ' . $this->startTime);
    }

    /**
     * @param string $startDate
     */
    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @param string $startTime
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return int
     */
    public function getPlanId(): int
    {
        return $this->planId;
    }

    /**
     * @param int $planId
     */
    public function setPlanId(int $planId): void
    {
        $this->planId = $planId;
    }
}