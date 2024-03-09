<?php

namespace App\Repositories\Contracts;

use App\Models\SoccerMatchesTeam;

interface SoccerMatchTeamRepositoryInterface
{
  /**
   * Method for create Soccer Match Team
   * 
   * @param array Soccer Match Team payload
   */
  public function create($payload): SoccerMatchesTeam;
}
