<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use App\Services\PointsCalculatorService;
use App\Services\StatisticsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{

    public function __construct(private GameService $gameService) {}

    /**
     * Display a list of all games with statistics.
     */
    public function gamesList(StatisticsService $statisticsService): View
    {
        $games = $statisticsService->getGamesList();

        return view('games.list', ['games' => $games['games']]);
    }

    /**
     * Show the form for creating a new game.
     */
    public function startNewGame(): View
    {
        $players = $this->gameService->getPlayersList();

        return view('games.select-players', ['players' => $players]);
    }

    /**
     * Creating new game
     * Adding players to game
     * Insert game id & selected players to session.
     */
    public function selectPlayers(Request $request): RedirectResponse
    {
        //Get id of the last game and create ID for new game.
        $this->gameService->createGameWithPlayers($request);

        //Redirect to route counting points
        return redirect()->route('games.pointsForm');
    }

    /**
     * 
     */
    public function pointsForm(): View
    {
        $players = $this->gameService->getPlayersListFromSession();
        return view('games.points', ['players' => $players]);
    }

    /**
     * Calculate player points and show the result.
     */
    public function pointsCalculate(Request $request, PointsCalculatorService $pointsCalculatorService): View
    {
        // Calculate points for each player
        $resultData = $pointsCalculatorService->pointsCalculator($request);

        // Create new game in database

        // Update players statistics

        // Display results
        return view('games.result', ['results' => $resultData['results'], 'winner' => $resultData['winner']]);
    }
}
