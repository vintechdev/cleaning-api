<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providerservicemaps;
class TotalCostCalculation extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
       // $this->app->bind(Providerservicemaps::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton( Providerservicemaps::class, function () {
            return GetTotal();
        });
        //
    }
}
