<?php

namespace App\Repository;

use App\SMSNotificationLogs;

class SmsNotificationLogRepo
{
    private function getModelClass(): SMSNotificationLogs
    {
        return new SMSNotificationLogs();
    }

    public function getAllPendingSmsList() {
        return $this->getModelClass()->newQuery()->with(['notificationlogs', 'notificationlogs.user'])
            ->where('status', 'pending')
            ->get();
    }

    public function create($data = []) {
        if (empty($data)) {
            return;
        }

        $this->getModelClass()->newQuery()
         ->create($data);
    }
}