<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'games',
        'wins',
        'deaths',
        'best',
        'average',
        'totalGold',
        'art5',
        'art7',
        'art10',
        'art12',
        'art15',
        'art17',
        'art20',
        'art25',
        'art30'
    ];
}
