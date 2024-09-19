<?php

namespace App\Services;

use App\DTOs\GamesListDTO;
use App\Models\Game;
use App\Models\Result;

class StatisticsService
{
    /**
     * Get list of all games and return data to controller
     */
    public function getGamesList(): array
    {

        $allGameIds = Game::pluck('id');

        if ($allGameIds->isEmpty()) {
            return ['games' => null];
        }

        $games = Game::whereIn('id', $allGameIds)->orderByDesc('id')->paginate(1);
        $gamesStatiscitcs = Result::whereIn('game_id', $allGameIds)->get()->groupBy('game_id');

        $gameDetails = $games->map(function ($game) use ($gamesStatiscitcs) {
            $gameId = $game->id;
            $winner = $game->winner;
            $statistics = $gamesStatiscitcs->get($gameId, collect());
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

    //
    public function getLastGameStatistics(): array
    {
        $data = [
            'lastGame' => null,
            'results' => null
        ];
        $lastGame = Game::latest()->first();
        if ($lastGame !== null) {
            $gameId = $lastGame->id;
            $results = Result::where('game_id', $gameId)->get();
            $data = [
                'lastGame' => $lastGame,
                'results' => $results
            ];
        }
        return $data;
    }
}
