<?php

namespace App\Services;

use App\DTOs\NewGameParams\GameDataDTO;
use App\Interfaces\GameResultProviderInterface;
use App\Models\Game;
use App\Models\Result;

class GameResultService implements GameResultProviderInterface
{
    public function getGameResult(int $gameId): array
    {
        $results = Result::where('game_id', $gameId)
            ->orderByDesc('total_points')
            ->orderByDesc('art30')
            ->orderByDesc('art25')
            ->orderByDesc('art20')
            ->orderByDesc('art17')
            ->orderByDesc('art15')
            ->orderByDesc('art12')
            ->orderByDesc('art10')
            ->orderByDesc('art7')
            ->orderByDesc('art5')
            ->get();

        $winner = Game::where('id', $gameId)->value('winner');

        $resultData = [
            'results' => $results,
            'winner' => $winner
        ];

        return $resultData;
    }
}
