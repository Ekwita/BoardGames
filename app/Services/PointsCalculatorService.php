<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Interfaces\SetWinnerInterface;
use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;
use App\Factories\GameFactory;
use App\Factories\OnePlayerResultFactory;
use App\Interfaces\GameResultServiceInterface;
use App\Interfaces\PlayerPointsServiceInterface;
use App\Interfaces\PointsCalculatorInterface;
use Illuminate\Http\Request;

class PointsCalculatorService implements PointsCalculatorInterface
{

    public function __construct(
        protected GameFactory $gameFactory,
        protected OnePlayerResultFactory $onePlayerResultFactory,
        protected GameResultServiceInterface $gameResultService,
        protected PlayerPointsServiceInterface $playerPointsService,
        protected SetWinnerInterface $setWinner,
    ) {}

    /**
     * Calculate points for each player
     */
    public function pointsCalculator(Request $request): array
    {
        //Create new game
        $gameData = $this->gameFactory->create($request);

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
        $this->setWinner($bestPlayer, $gameData);

        //Get saved game result
        $resultData = $this->gameResultService->getGameResult($gameData->id);


        session()->forget('gameData');

        return $resultData;
    }

    private function setWinner(PlayerPointsComparisonDTO $bestPlayer, GameDataDTO $gameData): void
    {
        if ($bestPlayer->bestPlayer != null) {
            $this->setWinner->handle($bestPlayer, $gameData);
        }
    }
}
