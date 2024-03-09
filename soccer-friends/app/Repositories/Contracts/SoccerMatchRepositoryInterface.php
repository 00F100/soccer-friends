<?php

namespace App\Repositories\Contracts;

use App\Models\SoccerMatch;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SoccerMatchRepositoryInterface
{
  /**
   * Get Soccer Match paginates, ordered and with count players
   * 
   * @param int Per page records
   * @param string Sort records
   * @param string Order records
   */
  public function paginateWithCountPlayersSelected(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator;

  /**
   * Get Soccer Match by id
   * 
   * @param string Soccer Match Id
   */
  public function find(string $id): SoccerMatch;

  /**
   * Get Soccer Match count
   */
  public function count(): int;

  /**
   * Create/Update Soccer Match using payload
   * 
   * @param array Soccer Match payload
   * @param string Soccer Match Id
   */
  public function save($payload, string $id = null): bool;

  /**
   * Hard delete Soccer Match
   * 
   * @param string Soccer Match Id
   */
  public function delete(string $id): bool;

  /**
   * Get Selected Players for Soccer Match
   * 
   * @param SoccerMatch Soccer Match Model
   */
  public function getSelectedPlayers(SoccerMatch $soccerMatch): array;

  /**
   * Get Soccer Match with Player counting confirmed
   */
  public function getSoccerMatchForGenerateTeam(string $soccerMatchId);

  /**
   * Get Next Soccer Match
   */
  public function getNextMatch(): SoccerMatch;

  /**
   * Get History Soccer Match
   */
  public function getHistoryMatches(): Collection;
}
