<?php

namespace App\Repository;

use App\UserNotification;

class UserNotificationRepository
{

    private function getModelClass()
    {
        return new UserNotification();
    }

    public function flexJoin($joinType = "inner"): string {
        if ($joinType == "left") {
          return "leftJoin";
        } else if ($joinType == "left") {
            return "rightJoin";
        }

        return "join";
    }

    public function getUserNotificationType(int $userId, string $type, $joinType = "inner")
    {
        // get join type and render as function in query
        $join = $this->flexJoin($joinType);

        $query = $this->getModelClass()->newQuery();

        return $query->{$join}("notification_settings",
            "notification_settings.id", "=", "user_notifications.notification_id")
            ->select([
                "user_notifications.*",
                "notification_settings.allow_email",
                "notification_settings.allow_sms",
                "notification_settings.allow_push",
            ])
            ->where("notification_settings.notification_type", $type)
            ->where("user_notifications.user_id", $userId)
            ->first();
    }
}