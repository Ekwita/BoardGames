<?php

namespace App\Services;

use App\DTOs\Players\CreatePlayerDTO;
use App\DTOs\Players\PlayerStatisticDTO;
use App\Interfaces\PlayerServiceInterface;
use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PlayerService implements PlayerServiceInterface
{
    public function getAllPlayers(): Collection
    {
        $user = Auth::user();
        return $user->players->map(function ($player) {
            return $player->mapToDto();
        });
    }

    public function createPlayer(CreatePlayerDTO $createPlayerDto): void
    {
        Player::create($createPlayerDto->toArray());
    }

    public function getPlayerById(string $playerId): PlayerStatisticDTO
    {
        $player = Player::findOrFail($playerId);
        return $player->mapToDto();
    }
}

