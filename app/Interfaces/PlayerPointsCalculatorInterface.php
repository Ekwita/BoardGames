<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PlayerPointsCalculatorInterface
{
    public function calculatePoints(Request $request, string $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact): array;
}
