<?php

namespace App\Interfaces;

use App\Http\Requests\PlayerPointRequest;

interface PointsCalculatorInterface
{
    public function pointsCalculator(PlayerPointRequest $request): array;
}
