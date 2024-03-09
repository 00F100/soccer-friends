<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Log;
use App\Models\Player;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepositoryEloquent extends RepositoryEloquent implements PlayerRepositoryInterface
{
  /**
   * Get Players paginates and ordered
   * 
   * @param int Per page records
   * @param string Sort records
   * @param string Order records
   */
  public function paginate(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator
  {
    return Player::orderBy($sort, $order)->paginate($perPage);
  }

  /**
   * Get Player by id
   * 
   * @param string Player Id
   */
  public function find(string $id): Player
  {
    return Player::findOrFail($id);
  }

  /**
   * Get Players Collection
   */
  public function get(): Collection
  {
    return Player::orderBy('goalkeeper', 'desc')
      ->orderBy('name', 'asc')
      ->get();
  }

  /**
   * Get Players count
   */
  public function count(): int
  {
    return Player::count();
  }

  /**
   * Create/Update Player using payload
   * 
   * @param array Player payload
   * @param string Player Id
   */
  public function save($payload, string $id = null): Player
  {
    return Player::updateOrCreate(
      compact('id'),
      $payload
    );
  }

  /**
   * Hard delete Player
   * 
   * @param string Player Id
   */
  public function delete(string $id): bool
  {
    $player = $this->find($id);

    if (!$player) {
      Log::error('Player not found', compact('id'));
      return false;
    }

    if ($player->soccerMatchesPlayer()->exists()) {
      Log::error('Error on try delete Player. Have Soccer Matches Player association', compact('id'));
      return false;
    }

    if ($player->soccerMatchesTeam()->exists()) {
      Log::error('Error on try delete Player. Have Soccer Matches Team association', compact('id'));
      return false;
    }

    return $player->delete();
  }
}
