<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Contracts\TeamsHelperInterface;
use App\Helpers\TeamsHelper;

class HelperServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->bind(TeamsHelperInterface::class, TeamsHelper::class);
  }
}
