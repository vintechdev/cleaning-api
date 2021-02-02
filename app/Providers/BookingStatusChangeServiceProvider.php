<?php

namespace App\Providers;

use App\Repository\BookingReqestProviderRepository;
use App\Services\Bookings\ApproveBookingStrategy;
use App\Services\Bookings\ArriveBookingStrategy;
use App\Services\Bookings\BookingServicesManager;
use App\Services\Bookings\BookingVerificationService;
use App\Services\Bookings\CompleteBookingStrategy;
use App\Services\Payments\StripePaymentProcessor;
use App\Services\RecurringBookingService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class BookingStatusChangeServiceProvider
 * @package App\Providers
 */
class BookingStatusChangeServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(CompleteBookingStrategy::class, function ($app) {
            return new CompleteBookingStrategy(
                $app->get(BookingReqestProviderRepository::class),
                $app->get(BookingVerificationService::class),
                $app->get(RecurringBookingService::class),
                $app->get(BookingServicesManager::class),
                $app->get(StripePaymentProcessor::class)
            );
        });

        $this->app->singleton(ArriveBookingStrategy::class, function ($app) {
            return new ArriveBookingStrategy(
                $app->get(BookingReqestProviderRepository::class),
                $app->get(BookingVerificationService::class),
                $app->get(RecurringBookingService::class),
                $app->get(StripePaymentProcessor::class)
            );
        });

        $this->app->singleton(ApproveBookingStrategy::class, function ($app) {
            return new ArriveBookingStrategy(
                $app->get(BookingReqestProviderRepository::class),
                $app->get(BookingVerificationService::class),
                $app->get(RecurringBookingService::class),
                $app->get(StripePaymentProcessor::class)
            );
        });
    }

    /**
     * @return array|string[]
     */
    public function provides()
    {
        return [
            CompleteBookingStrategy::class,
            ArriveBookingStrategy::class
        ];
    }
}