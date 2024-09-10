<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Result;
use App\Services\StatisticsService;
use Illuminate\View\View;

class HomePageController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index(StatisticsService $statisticsService): View
    {
        session()->flush();
        $data = $statisticsService->getLastGameStatistics();

        return view('welcome', [
            'results' => $data['results'],
            'game' => $data['lastGame']
        ]);
    }
}
