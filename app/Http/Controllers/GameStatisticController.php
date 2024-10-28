<?php

namespace App\Http\Controllers;

use App\Interfaces\StatisticInterface;
use Inertia\Inertia;
use Inertia\Response;

class GameStatisticController extends Controller
{
    public function __construct(protected StatisticInterface $statisticsService) {}

    /**
     * Display a list of all games with statistics.
     */
    public function gamesList(): Response
    {
        $games = $this->statisticsService->getGamesList();

        return Inertia::render('Games/List', ['games' => $games['games']]);
    }
}
