<?php

namespace App\Repositories\Eloquent;

use App\Models\SoccerMatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\SoccerMatchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SoccerMatchRepositoryEloquent extends RepositoryEloquent implements SoccerMatchRepositoryInterface
{
  /**
   * Get Soccer Match paginates, ordered and with count players
   * 
   * @param int Per page records
   * @param string Sort records
   * @param string Order records
   */
  public function paginateWithCountPlayersSelected(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator
  {
    return SoccerMatch::withCount('players as players_selected')
      ->orderBy($sort, $order)
      ->paginate($perPage);
  }

  /**
   * Get Soccer Match by id
   * 
   * @param string Soccer Match Id
   */
  public function find(string $id): SoccerMatch
  {
    return SoccerMatch::findOrFail($id);
  }

  /**
   * Get Soccer Match count
   */
  public function count(): int
  {
    return SoccerMatch::count();
  }

  /**
   * Create/Update Soccer Match using payload
   * 
   * @param array Soccer Match payload
   * @param string Soccer Match Id
   */
  public function save($payload, string $id = null): bool
  {
    try {
      $this->transaction(function() use ($payload, $id) {
        $soccerMatch = SoccerMatch::updateOrCreate(
          compact('id'),
          $payload
        );
        $soccerMatch->syncPlayers($payload['players']);
      });
      return true;
    } catch (\Exception $e) {
      Log::error('Error on try save Soccer Match', compact('e', 'id', 'payload'));
    }

    return false;
  }

  /**
   * Hard delete Soccer Match
   * 
   * @param string Soccer Match Id
   */
  public function delete(string $id): bool
  {
    $soccerMatch = $this->find($id);

    if (!$soccerMatch) {
      Log::error('Soccer Match not found', compact('id'));
      return false;
    }

    if ($soccerMatch->soccerMatchesPlayer()->exists()) {
      Log::error('Error on try delete Soccer Match. Have Soccer Matches Player association', compact('id'));
      return false;
    }

    if ($soccerMatch->soccerMatchesTeam()->exists()) {
      Log::error('Error on try delete Soccer Match. Have Soccer Matches Team association', compact('id'));
      return false;
    }

    return $soccerMatch->delete();
  }

  /**
   * Get Selected Players for Soccer Match
   * 
   * @param SoccerMatch Soccer Match Model
   */
  public function getSelectedPlayers(SoccerMatch $soccerMatch): array
  {
    return $soccerMatch
      ->players()
      ->orderBy('players.goalkeeper', 'desc')
      ->orderBy('players.name', 'asc')
      ->pluck('players.id')
      ->toArray();
  }

  /**
   * Get Soccer Match with Player confirmed
   * 
   * @param string Soccer Match Id
   */
  public function getSoccerMatchWithPlayersConfirmed(string $soccerMatchId): SoccerMatch
  {
    return SoccerMatch::with(['players' => function ($query) {
      $query->orderBy('name', 'asc');
      $query->where('confirm', true);
    }])
    ->where('id', $soccerMatchId)
    ->first();
  }

  /**
   * Get Next Soccer Match
   */
  public function getNextMatch(): SoccerMatch
  {
    return $this->getMatches(false, 'asc');
  }

  /**
   * Get History Soccer Match
   */
  public function getHistoryMatches(): Collection
  {
    return $this->getMatches(true, 'desc');
  }

  /**
   * Method for get Soccer Match next and history
   * 
   * @param bool If Soccer Match finished
   * @param string Order for date asc/desc
   */
  private function getMatches(bool $state, string $order): Collection | SoccerMatch
  {
    $result = SoccerMatch::with(['players' => function ($query) {
      $query->orderBy('name', 'asc');
    }])
      ->withCount(['players as players_confirmed_count' => function ($query) {
          $query->where('confirm', true);
          $query->where('goalkeeper', false);
      }])
      ->withCount(['players as players_confirmed_goalkeeper_count' => function ($query) {
          $query->where('confirm', true);
          $query->where('goalkeeper', true);
      }])
      ->where('finished', $state)
      ->orderBy('date', $order);
    
    if ($state)
      return $result->get();
    
    return $result->first();
  }
}
