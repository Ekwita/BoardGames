<?php

namespace App\Providers;

use App\Interfaces\StatisticInterface;
use App\Services\StatisticService;
use Illuminate\Support\ServiceProvider;

class StatisticServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StatisticInterface::class, StatisticService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
