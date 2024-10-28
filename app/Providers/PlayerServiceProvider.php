<?php

namespace App\Providers;

use App\Interfaces\PlayerServiceInterface;
use App\Services\PlayerService;
use Illuminate\Support\ServiceProvider;

class PlayerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PlayerServiceInterface::class, PlayerService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
