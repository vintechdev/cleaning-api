<?php


namespace App\Repository\Eloquent;


use App\NotificationLog;

class NotificationLogRepository extends AbstractBaseRepository
{
    protected function getModelClass(): string
    {
        return NotificationLog::class;
    }
}