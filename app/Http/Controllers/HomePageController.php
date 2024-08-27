<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Result;
use Illuminate\View\View;

class HomePageController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index(): View
    {
        session()->flush();
        $lastGame = Game::latest()->first();
        if ($lastGame !== null) {
            $gameId = $lastGame->id;
            $results = Result::where('game_id', $gameId)->get();
            return view('welcome', ['results' => $results, 'game' => $lastGame]);
        } else {
            return view('welcome');
        }
    }
}
