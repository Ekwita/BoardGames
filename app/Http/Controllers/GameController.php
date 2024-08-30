<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\GameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{

    public function __construct(private GameService $gameService) {}
    /**
     * Display a listing of the resource.
     */
    public function gamesList()
    {
        $games = $this->gameService->getGamesList();
        // dd($games);
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
        $gameData = $this->gameService->createData($request);

        //Put game and players data to session
        $request->session()->put('gameData', $gameData);


        //Redirect to route counting points
        return redirect()->route('games.pointsForm');
    }

    /**
     * 
     */
    public function pointsForm(): View
    {
        $gameData = session()->get('gameData');
        $players = $gameData->players;
        return view('games.points', ['players' => $players]);
    }

    /**
     * Calculate player points.
     */
    public function pointsCalculate(Request $request)
    {
        $gameData = $request->session()->get('gameData');

        $resultData = $this->gameService->pointsCalculator($gameData, $request);

        // dd($resultData);
        $request->session()->flush();
        return view('games.result', ['results' => $resultData['results'], 'winner' => $resultData['winner']]);
    }
}
