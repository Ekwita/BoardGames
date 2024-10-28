<?php

namespace App\Interfaces;

use App\DTOs\Players\CreatePlayerDTO;
use App\DTOs\Players\PlayerStatisticDTO;
use Illuminate\Support\Collection;

interface PlayerServiceInterface
{

    public function getAllPlayers(): Collection;
    public function createPlayer(CreatePlayerDTO $createPlayerDto): void;
    public function getPlayerById(int $playerId): PlayerStatisticDTO;
}
