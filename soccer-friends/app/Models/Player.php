<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Support\Str;

class Player extends Model
{
  /**
   * Table Player Model
   * @param string
   */
  protected $table = 'players';

  /**
   * Fields for Player Model
   * @param array
   */
  protected $fillable = ['name', 'level', 'goalkeeper'];

  /**
   * Get Soccer Match that belong to the Soccer Match Player
   */
  public function soccerMatches()
  {
    return $this->belongsToMany(SoccerMatch::class, 'soccer_matches_player');
  }

  /**
   * Get all of the Soccer Matches Team for the Player.
   */
  public function soccerMatchesTeam()
  {
    return $this->hasMany(SoccerMatchesTeam::class);
  }

  /**
   * Get all of the Soccer Matches PLayer for the Player
   */
  public function soccerMatchesPlayer()
  {
    return $this->hasMany(SoccerMatchesPlayer::class);
  }
}
