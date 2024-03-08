<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Log;
use App\Models\Player;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepositoryEloquent extends RepositoryEloquent implements PlayerRepositoryInterface
{
  public function paginate(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator
  {
    return Player::orderBy($sort, $order)->paginate($perPage);
  }

  public function find(string $id): Player
  {
    return Player::findOrFail($id);
  }

  public function get(): Collection
  {
    return Player::orderBy('goalkeeper', 'desc')
      ->orderBy('name', 'asc')
      ->get();
  }

  public function create($payload): Player
  {
    return Player::create($payload);
  }

  public function update(string $id, $payload): bool
  {
    $player = $this->find($id);
    return $player->update($payload);
  }

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
