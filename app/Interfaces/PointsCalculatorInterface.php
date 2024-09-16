<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PointsCalculatorInterface
{
    public function pointsCalculator(Request $request);
}
