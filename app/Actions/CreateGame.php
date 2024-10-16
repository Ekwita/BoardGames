<?php

namespace App\Actions;

use App\Models\Game;
use Illuminate\Http\Request;

class CreateGame
{
    public function execute(): void
    {
        Game::create();
    }
}