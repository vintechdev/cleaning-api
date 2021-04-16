<?php

namespace App\Services;

use App\Repository\BookingSummaryRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Collection;

class AdminSummaryManager
{
    /**
     * @var BookingSummaryRepository
     */
    private $bookingSummaryRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * AdminSummaryManager constructor.
     * @param BookingSummaryRepository $bookingSummaryRepository
     * @param UserRepository $userRepository
     */
    public function __construct(BookingSummaryRepository $bookingSummaryRepository, UserRepository $userRepository)
    {
        $this->bookingSummaryRepository = $bookingSummaryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $filters
     * @return int
     */
    public function getNewBookingCount(array $filters = []): int
    {
        return $this->bookingSummaryRepository->getNewBookings($filters)->count();
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public function getNewBookings(array $filters = []): Collection
    {
        return $this->bookingSummaryRepository->getNewBookings($filters);
    }

    /**
     * @param array $filters
     * @return int
     */
    public function getNewUserCount(array $filters = []): Int
    {
        return $this->userRepository->getNewUsers($filters)->count();
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public function getNewUsers(array $filters = []): Collection
    {
        return $this->userRepository->getNewUsers($filters);
    }
}