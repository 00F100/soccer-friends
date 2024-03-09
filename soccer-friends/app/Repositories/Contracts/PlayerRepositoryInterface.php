<?php

namespace App\Repositories\Contracts;

use App\Models\Player;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PlayerRepositoryInterface
{
  /**
   * Get Players paginates and ordered
   * 
   * @param int Per page records
   * @param string Sort records
   * @param string Order records
   */
  public function paginate(int $perPage = 10, string $sort = 'name', string $order = 'asc'): LengthAwarePaginator;

  /**
   * Get Player by id
   * 
   * @param string Player Id
   */
  public function find(string $id): Player;

  /**
   * Get Players Collection
   */
  public function get(): Collection;

  /**
   * Get Players count
   */
  public function count(): int;

  /**
   * Create/Update Player using payload
   * 
   * @param array Player payload
   * @param string Player Id
   */
  public function save($payload, string $id = null): Player;

  /**
   * Hard delete Player
   * 
   * @param string Player Id
   */
  public function delete(string $id): bool;
}
