<?php

namespace App\Repositories\Contracts;

interface SoccerMatchPlayerRepositoryInterface
{
  /**
   * Method for confirm Player into Soccer Match, using Soccer Match Player
   * 
   * @param string Soccer Match Id
   * @param string Player Id
   */
  public function confirm(string $soccerMatchId, string $playerId): bool;
}
