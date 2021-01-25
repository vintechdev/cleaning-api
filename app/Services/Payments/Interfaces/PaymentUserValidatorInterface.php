<?php

namespace App\Services\Payments\Interfaces;

use App\User;

/**
 * Interface PaymentUserValidatorInterface
 * @package App\Services\Payments\Interfaces
 */
interface PaymentUserValidatorInterface
{
    /**
     * @param User $user
     * @return bool
     */
    public function isUsersCardValid(User $user): bool;
}