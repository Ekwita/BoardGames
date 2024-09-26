<?php

namespace App\Actions;

use App\Models\Game;

class CreateGame
{
    public function execute(): void
    {
        Game::create();
    }
}