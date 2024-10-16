<?php

namespace App\Http\Controllers;

use App\Interfaces\PointsCalculatorInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class GamePointsController extends Controller
{
    public function __construct(protected PointsCalculatorInterface $pointsCalculator) {}

    /**
     * Calculate player points and show the result.
     */
    public function pointsCalculate(Request $request): Response
    {
        // Calculate points for each player

        $resultData = $this->pointsCalculator->pointsCalculator($request);


        return Inertia::render('Games/Result', [
            'results' => $resultData['results'],
            'winner' => $resultData['winner']
        ]);
        // Display results
        // return view('games.result', ['results' => $resultData['results'], 'winner' => $resultData['winner']]);
    }
}
