<?php

namespace App\Helpers\Contracts;

use App\Models\SoccerMatch;

interface TeamsHelperInterface
{
  /**
   * Method for get players by function
   * 
   * @param SoccerMatch Soccer Match instance
   * @param bool is Goalkeeper (function)
   */
  public function getPlayersByFunction(SoccerMatch $soccerMatch, bool $goalkeeper);
  
  /**
   * Method for generate teams using Players into Soccer Match
   * 
   * @param SoccerMatch Soocer Match instance
   * @param array Players Goalkeeper
   * @param array Players non Goalkeeper
   */
  public function generate(SoccerMatch $soccerMatch, $enableGoalkeeper, $enablePlayers);
}
