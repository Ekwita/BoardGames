<?php

namespace App\Services\Interfaces;

use App\DTOs\PlayerDTO;
use Illuminate\Support\Collection;

interface PlayerServiceInterface
{
    public function getAllPlayers(): Collection;
    public function createPlayer(PlayerDTO $playerDTO): PlayerDTO;
    public function getPlayerById(int $playerId): PlayerDTO;
    public function updatePlayer(PlayerDTO $playerDTO): void;
}
