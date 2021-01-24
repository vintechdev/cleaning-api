<?php

namespace App\Providers;

use App\Repository\BookingReqestProviderRepository;
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
                $this->app->get(BookingReqestProviderRepository::class),
                $this->app->get(BookingVerificationService::class),
                $this->app->get(RecurringBookingService::class),
                $this->app->get(BookingServicesManager::class),
                $this->app->get(StripePaymentProcessor::class)
            );
        });
    }

    /**
     * @return array|string[]
     */
    public function provides()
    {
        return [
            CompleteBookingStrategy::class
        ];
    }
}