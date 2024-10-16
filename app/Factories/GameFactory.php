<?php

namespace App\Factories;

use App\Models\Game;
use Illuminate\Http\Request;

class GameFactory
{
    public static function create(Request $request): Game
    {
        $user = $request->user();

        return Game::create([
            'user_id' => $user->id
        ]);
    }
}
