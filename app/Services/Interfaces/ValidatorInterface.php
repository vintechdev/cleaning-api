<?php

namespace App\Services\Interfaces;

/**
 * Interface ValidatorInterface
 * @package App\Services\Interfaces
 */
interface ValidatorInterface
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function isValid(): bool;
}