<?php

use Illuminate\Database\Seeder;

class NotificationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notificationTypes = [
            'transactional' => [
                'title' => 'Transactional',
                'description' => 'You will always receive important notifications about any payments, cancellations and your account.',
                'allow_email' => 0,
                'allow_sms' => 2,
                'allow_push' => 2,
                'default_email' => 1,
                'default_sms' => 0,
                'default_push' => 0,
                'display_user' => true,
                'display_provider' => true,
            ],
            'booking_updates' => [
                'title' => 'Booking updates',
                'description' => 'Receive updates on any new comments, private messages, booking status Changes, and reviews.',
                'allow_email' => 0,
                'allow_sms' => 1,
                'allow_push' => 0,
                'default_email' => 1,
                'default_sms' => 0,
                'default_push' => 1,
                'display_user' => true,
                'display_provider' => true,
            ],
            'new_booking_request_provider' => [
                'title' => 'New Booking request to provider',
                'description' => 'You’ll be instantly notified when the user has selected you for booking and send you requests.',
                'allow_email' => 0,
                'allow_sms' => 1,
                'allow_push' => 1,
                'default_email' => 1,
                'default_sms' => 0,
                'default_push' => 0,
                'display_user' => false,
                'display_provider' => true,
            ],
            'task_reminder' => [
                'title' => 'TASK REMINDERS',
                'description' => 'Friendly reminders if you’ve forgotten to accept an offer, booking reminder and leave a review.',
                'allow_email' => 1,
                'allow_sms' => 1,
                'allow_push' => 1,
                'default_email' => 0,
                'default_sms' => 0,
                'default_push' => 0,
                'display_user' => false,
                'display_provider' => false,
            ],
            'help_information' => [
                'title' => 'Help Information',
                'description' => 'Learn about how to earn or save more and other helpful tips and advice.',
                'allow_email' => 1,
                'allow_sms' => 1,
                'allow_push' => 1,
                'default_email' => 0,
                'default_sms' => 0,
                'default_push' => 0,
                'display_user' => false,
                'display_provider' => false,
            ],
            'updates_and_news_letters' => [
                'title' => 'UPDATES & NEWSLETTERS',
                'description' => 'Be the first to hear about new features, exciting updates, and promotions.',
                'allow_email' => 1,
                'allow_sms' => 1,
                'allow_push' => 1,
                'default_email' => 0,
                'default_sms' => 0,
                'default_push' => 0,
                'display_user' => false,
                'display_provider' => false,
            ]
        ];


        foreach ($notificationTypes as $type => $notificationType) {
            $notificationSetting = new \App\Notification();
            $notificationSetting->notification_type = $type;
            $notificationSetting->notification_name = $notificationType['title'];
            $notificationSetting->notification_description = $notificationType['description'];
            $notificationSetting->allow_email = $notificationType['allow_email'];
            $notificationSetting->allow_sms = $notificationType['allow_sms'];
            $notificationSetting->allow_push = $notificationType['allow_push'];
            $notificationSetting->default_email = $notificationType['default_email'];
            $notificationSetting->default_sms = $notificationType['default_sms'];
            $notificationSetting->default_push = $notificationType['default_push'];
            $notificationSetting->display_user = $notificationType['display_user'];
            $notificationSetting->display_provider = $notificationType['display_provider'];

            $notificationSetting->save();
        }

    }
}
