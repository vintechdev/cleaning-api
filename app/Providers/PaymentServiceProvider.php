<?php

namespace App\Providers;

use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Services\Payments\StripeService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class PaymentServiceProvider
 * @package App\Providers
 */
class PaymentServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(StripeService::class, function ($app) {
            return new StripeService(
                $this->app->get(StripeUserMetadataRepository::class),
                $app['config']['payment']['STRIPE_SECRET']
            );
        });
    }

    /**
     * @return array|string[]
     */
    public function provides()
    {
        return [
            StripeService::class
        ];
    }
}