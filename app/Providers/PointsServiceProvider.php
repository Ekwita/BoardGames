<?php

namespace App\Providers;

use App\Actions\Interfaces\PlayerResultCreateInterface;
use App\Actions\Interfaces\PlayerStatsUpdateInterface;
use App\Actions\SetWinner;
use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PlayerPointsServiceInterface;
use App\Interfaces\PointsCalculatorInterface;
use App\Actions\Interfaces\SetWinnerInterface;
use App\Services\PlayerPointsService;
use App\Services\PointsCalculatorService;
use App\Strategies\AlivePlayerPointsStrategy;
use App\Strategies\DeadPlayerPointsStrategy;
use Illuminate\Support\ServiceProvider;

class PointsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
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
        $this->app->bind(SetWinnerInterface::class, SetWinner::class);
        $this->app->bind(PointsCalculatorInterface::class, PointsCalculatorService::class);
        $this->app->bind(PlayerPointsServiceInterface::class, PlayerPointsService::class);

        $this->app->when(AlivePlayerPointsStrategy::class)->needs(PlayerResultCreateInterface::class)->give(AlivePlayerResultCreate::class);
        $this->app->when(AlivePlayerPointsStrategy::class)->needs(PlayerStatsUpdateInterface::class)->give(AlivePlayerStatsUpdate::class);
        $this->app->when(DeadPlayerPointsStrategy::class)->needs(PlayerResultCreateInterface::class)->give(DeadPlayerResultCreate::class);
        $this->app->when(DeadPlayerPointsStrategy::class)->needs(PlayerStatsUpdateInterface::class)->give(DeadPlayerStatsUpdate::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
