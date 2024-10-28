<?php

namespace App\Services;

use App\DTOs\GamesListDTO;
use App\Interfaces\StatisticInterface;
use Illuminate\Support\Facades\Auth;

class StatisticService implements StatisticInterface
{
    /**
     * Get list of all games and return data to controller
     */
    public function getGamesList(): array
    {

        $user = Auth::user();

        $games = $user->games()->orderByDesc('id')->paginate(1);

        if ($games->isEmpty()) {
            return ['games' => null];
        }

        $gameDetails = $games->map(function ($game) {
            $gameId = $game->id;
            $winner = $game->winner;
            $statistics = $game->results;
            $createdAt = $game->created_at ? $game->created_at->format('d-m-Y H:i:s') : null;

            return new GamesListDTO(
                gameId: $gameId,
                winner: $winner,
                statistics: $statistics,
                createdAt: $createdAt
            );
        });

        $paginatedGames = new \Illuminate\Pagination\LengthAwarePaginator(
            $gameDetails,
            $games->total(),
            $games->perPage(),
            $games->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return ['games' => $paginatedGames];
    }

    public function getLastGameStatistics(): array
    {
        $data = [
            'lastGame' => null,
            'results' => null
        ];

        if (Auth::check()) {
            $user = Auth::user();
            $lastGame = $user->games()->latest()->first();
        } else {
            $lastGame = null;
        }
        
        if ($lastGame !== null) {
            $results = $lastGame->results;
            $data = [
                'lastGame' => $lastGame,
                'results' => $results
            ];
        }
        return $data;
    }
}
