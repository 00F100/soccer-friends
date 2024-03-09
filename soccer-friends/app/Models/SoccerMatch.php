<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Support\Str;

class SoccerMatch extends Model
{
  /**
   * Table Soccer Match Model
   * @param string
   */
  protected $table = 'soccer_matches';

  /**
   * Fields for Soccer Match Model
   * @param array
   */
  protected $fillable = ['name', 'date', 'positions', 'finished'];

  /**
   * Method for save Players into Soccer Match Player
   */
  public function syncPlayers(array $players)
  {
    $this->players()->detach();

    if (count($players) > 0 && !empty($players[0])) {
      $playerIds = explode(',', $players[0]);

      $pivotData = collect($playerIds)->mapWithKeys(function ($playerId) {
        return [$playerId => ['id' => Str::uuid()]];
      });

      $this->players()->attach($pivotData->toArray());
    }
  }

  /**
   * Get Player that belong to the Soccer Match Player
   */
  public function players()
  {
    return $this->belongsToMany(Player::class, 'soccer_matches_player')
      ->orderBy('goalkeeper', 'desc')
      ->withPivot('confirm');
  }

  /**
   * Get all of the Soccer Matches Team for the Soccer Match.
   */
  public function soccerMatchesTeam()
  {
    return $this->hasMany(SoccerMatchesTeam::class, 'soccer_match_id')
      ->orderBy('goalkeeper', 'desc')
      ->orderBy('level', 'desc');
  }

  /**
   * Get all of the Soccer Matches Player for the Soccer Match.
   */
  public function soccerMatchesPlayer()
  {
    return $this->hasMany(SoccerMatchesPlayer::class);
  }

  /**
   * Change finish state and save Soccer Match
   */
  public function finish() {
    $this->finished = true;
    $this->save();
  }
}
