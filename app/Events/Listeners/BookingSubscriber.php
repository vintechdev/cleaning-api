<?php

namespace App\Events\Listeners;

use App\Bookingactivitylog;
use App\Bookingstatus;
use App\Events\BookingCreated;
use App\Events\BookingStatusChanged;
use App\Events\Interfaces\BookingEvent;
use App\Services\BookingEmailNotificationService;
use App\Services\BookingPushNotificationService;
use App\Services\Bookings\BookingActivityLogger;
use App\Services\BookingSmsNotificationService;
use App\Services\BookingStatusChangeEmailNotificationService;
use App\Services\BookingStatusChangePushNotificationService;
use App\Services\BookingStatusChangeSmsNotificationService;
use App\Services\CompositeBookingNotificationService;

/**
 * Class BookingStatusEventSubscriber
 * @package App\Events\Listeners
 */
class BookingSubscriber
{
    /**
     * @var BookingActivityLogger
     */
    private $bookingActivityLogger;

    /**
     * BookingSubscriber constructor.
     * @param BookingActivityLogger $bookingActivityLogger
     */
    public function __construct(BookingActivityLogger $bookingActivityLogger)
    {
        $this->bookingActivityLogger = $bookingActivityLogger;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            BookingStatusChanged::class,
            [BookingSubscriber::class, 'addToBookingActivityLogs']
        );

        $events->listen(
            BookingStatusChanged::class,
            [BookingSubscriber::class, 'sendBookingStatusChangeNotifications']
        );

        $events->listen(
            BookingCreated::class,
            [BookingSubscriber::class, 'addToBookingActivityLogs']
        );

         $events->listen(
            BookingCreated::class,
            [BookingSubscriber::class, 'sendBookingCreatedNotifications']
        ); 
    }

    public function sendBookingCreatedNotifications(BookingEvent $event)
    {
        /** @var CompositeBookingNotificationService $notificationService */
        $notificationService = app(CompositeBookingNotificationService::class);

        $notificationService
            ->add(app(BookingEmailNotificationService::class))
            ->add(app(BookingPushNotificationService::class))
            ->add(app(BookingSmsNotificationService::class))
            ->setBooking($event->getBooking());

        $notificationService->send();
    }

    public function sendBookingStatusChangeNotifications(BookingEvent $event)
    {
      /** @var CompositeBookingNotificationService $notificationService */
        $notificationService = app(CompositeBookingNotificationService::class);
    
        $notificationService
            ->add(app(BookingStatusChangeEmailNotificationService::class))
            ->add(app(BookingStatusChangePushNotificationService::class))
            ->add(app(BookingStatusChangeSmsNotificationService::class))
            ->setBooking($event->getBooking());

            //set another class
        $notificationService->send();
           
    }

    /**
     * @param BookingEvent $event
     */
    public function addToBookingActivityLogs(BookingEvent $event)
    {
        if($event instanceof BookingStatusChanged) {
            $action = Bookingactivitylog::ACTION_STATUS_CHANGED;
            $oldStatus = Bookingstatus::getStatusNameById($event->getOldStatus());
            $newStatus = Bookingstatus::getStatusNameById($event->getNewStatus());
            $detail = 'Status changed from ' . $oldStatus . ' to ' . $newStatus;
        }else{
            $action = Bookingactivitylog::ACTION_BOOKING_CREATED;
            $detail = 'New booking created';
        }

        $this->bookingActivityLogger->addLog($event->getBooking(), $event->getUser(), $action, $detail);
    }
}