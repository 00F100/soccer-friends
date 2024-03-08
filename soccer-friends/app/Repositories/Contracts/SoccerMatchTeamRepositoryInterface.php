<?php

namespace App\Repositories\Contracts;

use App\Models\SoccerMatchesTeam;

interface SoccerMatchTeamRepositoryInterface
{
    public function create($payload): SoccerMatchesTeam;
}
