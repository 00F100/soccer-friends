<?php

namespace App\Repositories\Contracts;

interface SoccerMatchPlayerRepositoryInterface
{
  public function confirm(string $soccerMatchId, string $playerId): bool;
}
