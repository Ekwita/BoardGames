<?php

namespace App\Providers;

use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;
use App\Interfaces\PlayerPointsCalculatorInterface;
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
            switch ($params['type']) {
                case 2:
                    return new AlivePlayerPointsStrategy($alivePlayerResultCreate,  $alivePlayerStatsUpdate);
                case 3:
                    return new AlivePlayerPointsStrategy($alivePlayerResultCreate,  $alivePlayerStatsUpdate);
                case 1:
                    return new DeadPlayerPointsStrategy($deadPlayerResultCreate, $deadPlayerStatsUpdate);
                default:
                    throw new Exception('Unknown status value');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
