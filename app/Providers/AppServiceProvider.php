<?php

namespace App\Providers;

use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;
use App\Interfaces\GameInterface;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PointsCalculatorInterface;
use App\Services\GameService;
use App\Services\PointsCalculatorService;
use App\Strategies\AlivePlayerPointsStrategy;
use App\Strategies\DeadPlayerPointsStrategy;
use Exception;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlayerPointsCalculatorInterface::class, function ($app, $params) {
            $alivePlayerResultCreate = new AlivePlayerResultCreate;
            $alivePlayerStatsUpdate = new AlivePlayerStatsUpdate;
            $deadPlayerResultCreate = new DeadPlayerResultCreate;
            $deadPlayerStatsUpdate = new DeadPlayerStatsUpdate;

            if ($params['type'] == 1) {
                return new DeadPlayerPointsStrategy($deadPlayerResultCreate, $deadPlayerStatsUpdate);
            } else {
                return new AlivePlayerPointsStrategy($alivePlayerResultCreate,  $alivePlayerStatsUpdate);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(PointsCalculatorInterface::class, PointsCalculatorService::class);
        $this->app->bind(GameInterface::class, GameService::class);
    }
}
