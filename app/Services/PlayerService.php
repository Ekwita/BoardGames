<?php

namespace App\Services;

use App\DTOs\PlayerDTO;
use App\Models\Player;
use App\Services\Interfaces\PlayerServiceInterface;
use Illuminate\Support\Collection;

class PlayerService implements PlayerServiceInterface
{
    public function getAllPlayers(): Collection
    {
        return Player::all()->map(function ($player) {
            return $this->mapToDTO($player);
        });
    }

    public function createPlayer(PlayerDTO $playerDTO): PlayerDTO
    {
        $player = Player::create([
            'player_name' => $playerDTO->player_name,
            'games' => $playerDTO->games,
            'wins' => $playerDTO->wins,
            'deaths' => $playerDTO->deaths,
            'best' => $playerDTO->best,
            'average' => $playerDTO->average,
            'totalGold' => $playerDTO->totalGold,
            'art5' => $playerDTO->art5,
            'art7' => $playerDTO->art7,
            'art10' => $playerDTO->art10,
            'art12' => $playerDTO->art12,
            'art15' => $playerDTO->art15,
            'art17' => $playerDTO->art17,
            'art20' => $playerDTO->art20,
            'art25' => $playerDTO->art25,
            'art30' => $playerDTO->art30,
        ]);

        return $this->mapToDTO($player);
    }

    public function getPlayerById(int $playerId): PlayerDTO
    {
        $player = Player::findOrFail($playerId);
        return $this->mapToDTO($player);
    }

    public function updatePlayer(PlayerDTO $playerDTO): void
    {
        $player = Player::findOrFail($playerDTO->id);
        $player->update([
            'player_name' => $playerDTO->player_name,
            'games' => $playerDTO->games,
            'wins' => $playerDTO->wins,
            'deaths' => $playerDTO->deaths,
            'best' => $playerDTO->best,
            'average' => $playerDTO->average,
            'totalGold' => $playerDTO->totalGold,
            'art5' => $playerDTO->art5,
            'art7' => $playerDTO->art7,
            'art10' => $playerDTO->art10,
            'art12' => $playerDTO->art12,
            'art15' => $playerDTO->art15,
            'art17' => $playerDTO->art17,
            'art20' => $playerDTO->art20,
            'art25' => $playerDTO->art25,
            'art30' => $playerDTO->art30,
        ]);
    }

    private function mapToDTO(Player $player): PlayerDTO
    {
        return new PlayerDTO(
            id: $player->id,
            player_name: $player->player_name,
            games: $player->games,
            wins: $player->wins,
            deaths: $player->deaths,
            best: $player->best,
            average: $player->average,
            totalGold: $player->totalGold,
            art5: $player->art5,
            art7: $player->art7,
            art10: $player->art10,
            art12: $player->art12,
            art15: $player->art15,
            art17: $player->art17,
            art20: $player->art20,
            art25: $player->art25,
            art30: $player->art30
        );
    }
}
