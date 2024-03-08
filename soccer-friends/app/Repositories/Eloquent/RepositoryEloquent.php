<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class RepositoryEloquent {
  public function transaction($callback)
  {
    DB::beginTransaction();
    try {
      $callback($this);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Exception on try transact', compact('e'));
      throw $e;
    }
  }
}