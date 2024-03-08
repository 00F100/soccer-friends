<?php

namespace App\Repositories\Eloquent;

use App\Models\SoccerMatchesTeam;
use App\Repositories\Contracts\SoccerMatchTeamRepositoryInterface;

class SoccerMatchTeamRepositoryEloquent extends RepositoryEloquent implements SoccerMatchTeamRepositoryInterface
{
  public function create($payload): SoccerMatchesTeam
  {
    return SoccerMatchesTeam::create($payload);
  }
}
