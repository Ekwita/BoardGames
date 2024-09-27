<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\View\View;

class GameStatisticsController extends Controller
{
    public function __construct(protected StatisticsService $statisticsService) {}

    /**
     * Display a list of all games with statistics.
     */
    public function gamesList(): View
    {
        $games = $this->statisticsService->getGamesList();

        return view('games.list', ['games' => $games['games']]);
    }
}
