<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerPointsRequest;
use App\Interfaces\PointsCalculatorInterface;
use Inertia\Inertia;
use Inertia\Response;

class GamePointController extends Controller
{
    public function __construct(protected PointsCalculatorInterface $pointsCalculator) {}

    /**
     * Calculate player points and show the result.
     */
    public function pointsCalculate(PlayerPointsRequest $request): Response
    {
        $resultData = $this->pointsCalculator->pointsCalculator($request);

        return Inertia::render('Games/Result', [
            'results' => $resultData['results'],
            'winner' => $resultData['winner']
        ]);
    }
}
