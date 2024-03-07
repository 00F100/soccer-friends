<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Support\Str;

class Player extends Model
{
    protected $table = 'players';
    protected $fillable = ['name', 'level', 'goalkeeper'];

    public function soccerMatches()
    {
        return $this->belongsToMany(SoccerMatch::class, 'soccer_matches_player');
    }

    public function soccerMatchesTeam()
    {
        return $this->hasMany(SoccerMatchesTeam::class);
    }
}
