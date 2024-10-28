<?php

namespace App\Providers;

use App\Interfaces\GameInterface;
use App\Interfaces\GameResultServiceInterface;
use App\Services\GameResultService;
use App\Services\GameService;



use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GameInterface::class, GameService::class);
        $this->app->bind(GameResultServiceInterface::class, GameResultService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
