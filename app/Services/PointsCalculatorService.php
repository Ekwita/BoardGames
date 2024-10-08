<?php

namespace App\Services;

use App\Actions\CreateGame;
use App\Actions\SetWinner;
use App\Interfaces\GameResultProviderInterface;
use App\Interfaces\PlayerPointsServiceInterface;
use App\Interfaces\PointsCalculatorInterface;
use Illuminate\Http\Request;

class PointsCalculatorService implements PointsCalculatorInterface
{

    public function __construct(
        protected CreateGame $createGame,
        protected PlayerPointsServiceInterface $playerPointsService,
        protected GameResultProviderInterface $gameResultProvider,
        protected SetWinner $setWinner
    ) {}
    /**
     * Calculate points for each player
     */
    public function pointsCalculator(Request $request): array
    {
        $gameData = session()->get('gameData');
        $selectedPlayers = $gameData->allPlayersResults->playersResults;

        //Create new game in database
        $this->createGame->execute();

        //Select best player after calculating points
        $bestPlayer = $this->playerPointsService->calculate($request, $selectedPlayers, $gameData);

        //Set the winner
        if ($bestPlayer != null) {
            $this->setWinner->handle($gameData, $bestPlayer);
        }

        //Get saved game result
        $resultData = $this->gameResultProvider->getGameResult($gameData);

        $request->session()->flush();

        return $resultData;
    }
}
