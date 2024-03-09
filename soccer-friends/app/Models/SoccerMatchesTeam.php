<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Support\Str;

class SoccerMatchesTeam extends Model
{
    /**
     * Table Soccer Matches Team Model
     * @param string
     */
    protected $table = 'soccer_matches_team';

    /**
     * Fields for Soccer Matches Team Model
     * @param array
     */
    protected $fillable = ['soccer_match_id', 'player_id', 'side', 'level', 'goalkeeper'];

    /**
     * Get the Player that wrote the Soccer Match.
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
