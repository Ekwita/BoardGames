<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Inertia\Inertia;
use Inertia\Response;

class GameStatisticsController extends Controller
{
    public function __construct(protected StatisticsService $statisticsService) {}

    /**
     * Display a list of all games with statistics.
     */
    public function gamesList(): Response
    {
        $games = $this->statisticsService->getGamesList();

        return Inertia::render('Games/List', ['games' => $games['games']]);
    }
}
