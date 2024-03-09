<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Support\Str;

class SoccerMatchesPlayer extends Model
{
  /**
   * Table Soccer Matches Player Model
   * @param string
   */
  protected $table = 'soccer_matches_player';

  /**
   * Fields for Soccer Matches Player Model
   * @param array
   */
  protected $fillable = ['soccer_match_id', 'player_id', 'confirm'];

  /**
   * Method for change state confirmed
   */
  public function confirm() {
    $this->confirm = true;
    $this->save();
  }
}
