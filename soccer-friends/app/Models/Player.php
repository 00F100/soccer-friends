<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    public function soccerMatches()
    {
        return $this->belongsToMany(SoccerMatch::class, 'soccer_matches_player');
    }
}
