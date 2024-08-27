<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function gamesList(): View
    {

        $allGameIds = Game::pluck('id');
        if ($allGameIds->isEmpty()) {
            $gamesStatistics = null;
        } else {
            $gamesStatistics = Result::whereIn('game_id', $allGameIds)->get()->groupBy('game_id');
        }


        if ($gamesStatistics != null) {

            $gameIds = $gamesStatistics->keys();
            $games = Game::whereIn('id', $gameIds)->orderByDesc('id')->paginate(1);
            $winner = '';
            foreach ($games as $game) {
                $gameId = $game->id;
                $winner = Game::where('id', $gameId)->value('winner');
            }
            return view('games.list', ['games' => $games, 'gameStatistics' => $gamesStatistics, 'winner' => $winner]);
        } else {
            return view('games.list', ['games' => null]);
        }
    }

    /**
     * Show the form for creating a new game.
     */
    public function newGame(): View
    {
        $players = Player::all();
        return view('games.new-game', ['players' => $players]);
    }

    /**
     * Creating new game
     * Adding players to game
     * Insert game id & selected players to session.
     */
    public function selectPlayers(Request $request)
    {
        //Get id of the last game and create ID for new game.

        $latestGame = Game::latest()->first();
        $lastGameId = $latestGame ? $latestGame->id : 0;
        $newGameId = $lastGameId + 1;

        //Get players from request.

        $selectedPlayers = [];

        for ($playerNumber = 1; $playerNumber <= 6; $playerNumber++) {
            $key = 'player' . $playerNumber;
            $inputValue = $request->input($key);

            if ($inputValue !== null) {
                $selectedPlayers[$key] = $inputValue;
            }
        }

        //Put game and players data to session

        $request->session()->put('game_id', $newGameId);
        $request->session()->put('players', $selectedPlayers);

        //Redirect to route counting points
        return redirect()->route('games.pointsForm');
    }

    /**
     * 
     */
    public function pointsForm()
    {
        $players = session()->get('players');
        return view('games.points', ['players' => $players]);
    }

    /**
     * Calculate player points.
     */
    public function pointsCalculate(Request $request)
    {
        $gameId = $request->session()->get('game_id');
        $players = $request->session()->get('players'); // Players names

        $bestScore = 0;
        $bestArticaft = 0;

        Game::create();

        $bestPlayer = '';
        foreach ($players as $player) {

            $playerId = Player::where('player_name', $player)->value('id');

            $status = $request->input('status_' . $player);

            $statusPoints = ($status == 3) ? 20 : 0;
            $playerBestArtifact = 0;
            $totalPoints = 0;
            if ($status != 1) {

                $gold = ($request->input('gold_' . $player) != null) ? $request->input('gold_' . $player) : 0;
                $tokens = $request->input('tokens_' . $player);
                $cards = $request->input('cards_' . $player);


                $artifacts = [
                    'art5_' . $player => 5,
                    'art7_' . $player => 7,
                    'art10_' . $player => 10,
                    'art12_' . $player => 12,
                    'art15_' . $player => 15,
                    'art17_' . $player => 17,
                    'art20_' . $player => 20,
                    'art25_' . $player => 25,
                    'art30_' . $player => 30,
                ];

                $totalArtifactsPoints = 0;

                foreach ($artifacts as $artifactName => $artifactPoints) {
                    if ($request->has($artifactName)) {
                        $totalArtifactsPoints += $artifactPoints;
                        if ($artifactPoints > $playerBestArtifact) {
                            $playerBestArtifact = $artifactPoints;
                        }
                    }
                }

                $totalPoints = $statusPoints + $totalArtifactsPoints + $gold + $tokens + $cards;

                $data = [
                    'player' => $player,
                    'status' => $status,
                    'gold' => $gold,
                    'tokens' => $tokens,
                    'cards' => $cards,
                    'totalPoints' => $totalPoints
                ];


                Result::create([
                    'game_id' => $gameId,
                    'player_id' => $playerId,
                    'player_name' => $data['player'],
                    'status' => $data['status'],
                    'art5' => $request->has('art5_' . $data['player']),
                    'art7' => $request->has('art7_' . $data['player']),
                    'art10' => $request->has('art10_' . $data['player']),
                    'art12' => $request->has('art12_' . $data['player']),
                    'art15' => $request->has('art15_' . $data['player']),
                    'art17' => $request->has('art17_' . $data['player']),
                    'art20' => $request->has('art20_' . $data['player']),
                    'art25' => $request->has('art25_' . $data['player']),
                    'art30' => $request->has('art30_' . $data['player']),
                    'gold' => $data['gold'],
                    'tokens' => $data['tokens'],
                    'cards' => $data['cards'],
                    'total_points' => $data['totalPoints'],
                ]);

                $playerToUpdate = Player::where('player_name', $player);
                $playerToUpdate->incrementEach([
                    'games' => 1,
                    'totalgold' => $gold,
                    'art5' => $request->has('art5_' . $player) ? 1 : 0,
                    'art7' => $request->has('art7_' . $player) ? 1 : 0,
                    'art10' => $request->has('art10_' . $player) ? 1 : 0,
                    'art12' => $request->has('art12_' . $player) ? 1 : 0,
                    'art15' => $request->has('art15_' . $player) ? 1 : 0,
                    'art17' => $request->has('art17_' . $player) ? 1 : 0,
                    'art20' => $request->has('art20_' . $player) ? 1 : 0,
                    'art25' => $request->has('art25_' . $player) ? 1 : 0,
                    'art30' => $request->has('art30_' . $player) ? 1 : 0,
                ]);
            } else {
                Result::create([
                    'game_id' => $gameId,
                    'player_id' => $playerId,
                    'player_name' => $player,
                    'status' => $status,
                    'total_points' => '0',
                ]);

                Player::where('player_name', $player)->incrementEach([
                    'games' => 1,
                    'deaths' => 1,
                ]);
            }

            if ($totalPoints > $bestScore || $totalPoints == $bestScore && $playerBestArtifact > $bestArticaft) {
                $bestScore = $totalPoints;
                $bestArticaft = $playerBestArtifact;
                $bestPlayer = $player;
            }
            if ($totalPoints > Player::where('player_name', $player)->value('best')) {
                Player::where('player_name', $player)->update(['best' => $totalPoints]);
            }
        }
        if ($bestPlayer != null) {
            Game::where('id', $gameId)->update(['winner' => $bestPlayer]);
            Player::where('player_name', $bestPlayer)->increment('wins', 1);
        }

        $results = Result::where('game_id', $gameId)
            ->orderByDesc('total_points')
            ->orderByDesc('art30')
            ->orderByDesc('art25')
            ->orderByDesc('art20')
            ->orderByDesc('art17')
            ->orderByDesc('art15')
            ->orderByDesc('art12')
            ->orderByDesc('art10')
            ->orderByDesc('art7')
            ->orderByDesc('art5')
            ->get();


        $winner = Game::where('id', $gameId)->value('winner');

        $request->session()->flush();
        return view('games.result', ['results' => $results, 'winner' => $winner]);
    }
}
