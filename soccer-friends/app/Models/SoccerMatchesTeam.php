<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Support\Str;

class SoccerMatchesTeam extends Model
{
    protected $table = 'soccer_matches_team';
    protected $fillable = ['soccer_match_id', 'player_id', 'side', 'level', 'goalkeeper'];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
