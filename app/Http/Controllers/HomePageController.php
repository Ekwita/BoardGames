<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomePageController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index(StatisticsService $statisticsService): Response
    {
        session()->forget('gameData');
        $data = $statisticsService->getLastGameStatistics();

        return Inertia::render('Welcome', [
            'results' => $data['results'] ? $data['results']->toArray() : [],
            'game' => $data['lastGame'] ? $data['lastGame']->toArray() : null,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'auth' => Auth::check() ? [
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
            ] : null,
        ]);
    }
}
