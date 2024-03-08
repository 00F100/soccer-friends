<?php

namespace App\Repositories\Contracts;

use App\Models\Player;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PlayerRepositoryInterface
{
    public function paginate(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator;
    public function find(string $id): Player;
    public function get(): Collection;
    public function create($payload): Player;
    public function update(string $id, $payload): bool;
    public function delete(string $id): bool;
}
