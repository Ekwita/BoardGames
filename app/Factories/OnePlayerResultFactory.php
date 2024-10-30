<?php

namespace App\Factories;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\SelectedPlayerDTO;
use App\Http\Requests\PlayerPointRequest;

class OnePlayerResultFactory
{
    public function createDto(GameDataDTO $gameData, SelectedPlayerDTO $selectedPlayer, PlayerPointRequest $request): OnePlayerResultDTO
    {
        return new OnePlayerResultDTO(
            gameId: $gameData->id,
            playerId: $selectedPlayer->playerId,
            playerName: $selectedPlayer->playerName,
            status: $request->input('status_' . $selectedPlayer->playerName),
            art5: $request->has('art5_' . $selectedPlayer->playerName),
            art7: $request->has('art7_' . $selectedPlayer->playerName),
            art10: $request->has('art10_' . $selectedPlayer->playerName),
            art12: $request->has('art12_' . $selectedPlayer->playerName),
            art15: $request->has('art15_' . $selectedPlayer->playerName),
            art17: $request->has('art17_' . $selectedPlayer->playerName),
            art20: $request->has('art20_' . $selectedPlayer->playerName),
            art25: $request->has('art25_' . $selectedPlayer->playerName),
            art30: $request->has('art30_' . $selectedPlayer->playerName),
            gold: $request->input('gold_' . $selectedPlayer->playerName) ?? 0,
            tokens: $request->input('tokens_' . $selectedPlayer->playerName) ?? 0,
            cards: $request->input('cards_' . $selectedPlayer->playerName) ?? 0,
        );
    }
}
