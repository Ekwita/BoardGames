<?php

namespace App\Interfaces;

interface StatisticInterface
{
    public function getGamesList(): array;
    public function getLastGameStatistics(): array;
    
}
