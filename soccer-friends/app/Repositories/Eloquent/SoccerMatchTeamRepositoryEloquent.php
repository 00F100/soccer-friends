<?php

namespace App\Repositories\Eloquent;

use App\Models\SoccerMatchesTeam;
use App\Repositories\Contracts\SoccerMatchTeamRepositoryInterface;

class SoccerMatchTeamRepositoryEloquent extends RepositoryEloquent implements SoccerMatchTeamRepositoryInterface
{
  /**
   * Method for create Soccer Match Team
   * 
   * @param array Soccer Match Team payload
   */
  public function create($payload): SoccerMatchesTeam
  {
    return SoccerMatchesTeam::create($payload);
  }
}
