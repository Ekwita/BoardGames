<?php

namespace App\Actions\PlayersStats;

use App\Actions\Interfaces\PlayerStatsUpdateInterface;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Player;

class AlivePlayerStatsUpdate implements PlayerStatsUpdateInterface
{
    public function handle(OnePlayerResultDTO $dto): void
    {
        $playerToUpdate = Player::where('id', $dto->playerId);
        $playerToUpdate->incrementEach([
            'games' => 1,
            'totalgold' => $dto->gold,
            'art5' => $dto->art5 ? 1 : 0,
            'art7' => $dto->art7 ? 1 : 0,
            'art10' => $dto->art10 ? 1 : 0,
            'art12' => $dto->art12 ? 1 : 0,
            'art15' => $dto->art15 ? 1 : 0,
            'art17' => $dto->art17 ? 1 : 0,
            'art20' => $dto->art20 ? 1 : 0,
            'art25' => $dto->art25 ? 1 : 0,
            'art30' => $dto->art30 ? 1 : 0,
        ]);

        if ($dto->totalPoints > $playerToUpdate->value('best')) {
            $playerToUpdate->update(['best' => $dto->totalPoints]);
        }
    }
}
