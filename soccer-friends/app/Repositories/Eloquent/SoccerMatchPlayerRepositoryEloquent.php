<?php

namespace App\Repositories\Eloquent;

use App\Models\SoccerMatchesPlayer;
use App\Repositories\Contracts\SoccerMatchPlayerRepositoryInterface;

class SoccerMatchPlayerRepositoryEloquent extends RepositoryEloquent implements SoccerMatchPlayerRepositoryInterface
{
  /**
   * Method for confirm Player into Soccer Match, using Soccer Match Player
   * 
   * @param string Soccer Match Id
   * @param string Player Id
   */
  public function confirm(string $soccerMatchId, string $playerId): bool
  {
    $soccerMatchesPlayer = SoccerMatchesPlayer::where('soccer_match_id', $soccerMatchId)
      ->where('player_id', $playerId)
      ->first();

    if ($soccerMatchesPlayer) {
      $soccerMatchesPlayer->confirm();
      return true;
    }
    
    return false;
  }
}
