<?php

namespace App\Services;

use App\Actions\SetWinner;
use App\DTOs\AllPlayersListDTO;
use App\DTOs\NewGameParams\AllPlayersResultsDTO;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;
use App\Factories\GameFactory;
use App\Factories\OnePlayerResultFactory;
use App\Http\Requests\PlayerPointsRequest;
use App\Interfaces\GameResultProviderInterface;
use App\Interfaces\PlayerPointsServiceInterface;
use App\Interfaces\PointsCalculatorInterface;
use Illuminate\Http\Request;

class PointsCalculatorService implements PointsCalculatorInterface
{

    public function __construct(
        protected PlayerPointsServiceInterface $playerPointsService,
        protected GameFactory $gameFactory,
        protected GameResultProviderInterface $gameResultProvider,
        protected SetWinner $setWinner,
        protected OnePlayerResultFactory $onePlayerResultFactory,
    ) {}

    /**
     * Calculate points for each player
     */
    public function pointsCalculator(Request $request): array
    {
        //Create new game
        $gameData = $this->gameFactory->create($request);

        // session()->put('gameData', $gameData);

        $selectedPlayersObject = session()->get('selectedPlayers');
        $selectedPlayers = $selectedPlayersObject->selectedPlayers;

        $playerPoints = new PlayerPointsComparisonDTO();

        foreach ($selectedPlayers as $selectedPlayer) {
            //Create DTO form request data
            $dto = $this->onePlayerResultFactory->createDto($gameData, $selectedPlayer, $request);

            //Select best player after calculating points
            $bestPlayer = $this->playerPointsService->calculate($dto, $playerPoints);
        }

        //Set the winner
        if ($bestPlayer->bestPlayer != null) {
            $this->setWinner->handle($bestPlayer->bestPlayer, $gameData->id);
        }

        //Get saved game result
        $resultData = $this->gameResultProvider->getGameResult($gameData->id);


        session()->forget('gameData');

        return $resultData;
    }
}
