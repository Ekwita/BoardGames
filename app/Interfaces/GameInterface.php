<?php

namespace App\Interfaces;

use App\DTOs\AllPlayersListDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface GameInterface
{
    public function getPlayersList(): AllPlayersListDTO;
    public function createGameWithPlayers(Request $request): void;
    public function getPlayersListFromSession(): Collection;
}
