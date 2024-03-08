<?php

namespace App\Repositories\Eloquent;

use App\Models\SoccerMatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\SoccerMatchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SoccerMatchRepositoryEloquent extends RepositoryEloquent implements SoccerMatchRepositoryInterface
{
  public function paginateWithCountPlayersSelected(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator
  {
    return SoccerMatch::withCount('players as players_selected')
      ->orderBy($sort, $order)
      ->paginate($perPage);
  }

  public function find(string $id): SoccerMatch
  {
    return SoccerMatch::findOrFail($id);
  }

  public function save($payload, string $id = null): bool
  {
    DB::beginTransaction();

    try {
      $soccerMatch = SoccerMatch::updateOrCreate(
        compact('id'),
        $payload
      );
      $soccerMatch->syncPlayers($payload['players']);
      DB::commit();
      return true;
    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error on try save Soccer Match', compact('e', 'payload'));
    }

    return false;
  }

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

  public function getSelectedPlayers(SoccerMatch $soccerMatch): array
  {
    return $soccerMatch
      ->players()
      ->orderBy('players.goalkeeper', 'desc')
      ->orderBy('players.name', 'asc')
      ->pluck('players.id')
      ->toArray();
  }

  public function getSoccerMatchForGenerateTeam(string $soccerMatchId)
  {
    $soccerMatch = SoccerMatch::with(['players' => function ($query) {
      $query->orderBy('name', 'asc');
      $query->where('confirm', true);
    }])
    ->where('id', $soccerMatchId)
    ->first();

    if(!$soccerMatch) {
      Log::error('Soccer Match not found', compact('soccerMatchId'));
      return null;
    }

    if ($soccerMatch->players->count() < $soccerMatch->positions) {
      Log::error('More players are needed to start the match', compact('soccerMatchId'));
      return null;
    }

    $goalkeepers = $soccerMatch->players->filter(function($player) {
      return $player->goalkeeper == true;
    });

    if (count($goalkeepers) < 2) {
      Log::error('More goalkeepers are needed to start the match', compact('soccerMatchId'));
      return null;
    }

    $enableGoalkeeper = [];
    $enablePlayers = [];

    foreach($soccerMatch->players as $player) {
        if ($player->pivot->confirm) {
            if ($player->goalkeeper) {
                $enableGoalkeeper[] = $player;
            } else {
                $enablePlayers[] = $player;
            }
        }
    }

    return compact('enableGoalkeeper', 'enablePlayers', 'soccerMatch');
  }

  public function getNextMatch(): SoccerMatch
  {
    return $this->getMatches(false, 'asc');
  }

  public function getHistoryMatches(): Collection
  {
    return $this->getMatches(true, 'desc');
  }

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
