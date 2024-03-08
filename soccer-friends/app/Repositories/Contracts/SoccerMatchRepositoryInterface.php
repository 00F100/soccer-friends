<?php

namespace App\Repositories\Contracts;

use App\Models\SoccerMatch;
use Illuminate\Pagination\LengthAwarePaginator;

interface SoccerMatchRepositoryInterface
{
    public function paginateWithCountPlayersSelected(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator;
    public function find(string $id): SoccerMatch;
    public function save($payload, string $id = null): bool;
    public function delete(string $id): bool;
    public function getSelectedPlayers(SoccerMatch $soccerMatch): array;
    public function getNextMatch(): SoccerMatch;
    public function getSoccerMatchForGenerateTeam(string $soccerMatchId);
}
