<?php

namespace App\Services\Interfaces;

/**
 * Interface NotificationInterface
 * @package App\Services\Interfaces
 */
interface NotificationInterface
{
    /**
     * @return boolean
     */
    public function send():bool;
}