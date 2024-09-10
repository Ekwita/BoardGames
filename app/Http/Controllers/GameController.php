<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\GameService;
use App\Services\StatisticsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{

    public function __construct(private GameService $gameService) {}
    
    /**
     * Display a listing of the resource.
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
        $players = Player::all();
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
        $this->gameService->createData($request);

        //Redirect to route counting points
        return redirect()->route('games.pointsForm');
    }

    /**
     * 
     */
    public function pointsForm(): View
    {
        $players = $this->gameService->getPlayers();
        return view('games.points', ['players' => $players]);
    }



    /**
     * Calculate player points and show the result.
     */
    public function pointsCalculate(Request $request): View
    {
        $resultData = $this->gameService->pointsCalculator($request);

        return view('games.result', ['results' => $resultData['results'], 'winner' => $resultData['winner']]);
    }
}
