<?php

namespace App\Interfaces;

use App\Http\Requests\PlayerPointsRequest;
use Illuminate\Http\Request;

interface PointsCalculatorInterface
{
    public function pointsCalculator(Request $request): array;
}
