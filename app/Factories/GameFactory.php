<?php

namespace App\Factories;

use App\DTOs\NewGameParams\GameDataDTO;
use App\Models\Game;
use Illuminate\Http\Request;

class GameFactory
{
    public function create(Request $request): GameDataDTO
    {
        $user = $request->user();

        $game = Game::create([
            'user_id' => $user->id
        ]);

        return new GameDataDTO($game->id);

    }
}
