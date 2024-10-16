<?php

namespace App\Factories;

use App\Models\Game;
use Illuminate\Http\Request;

class GameFactory
{
    public static function create(Request $request): void
    {
        $user = $request->user();

        Game::create([
            'user_id' => $user->id
        ]);
    }
}
