<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use App\Repositories\Contracts\SoccerMatchRepositoryInterface;
use App\Repositories\Contracts\SoccerMatchPlayerRepositoryInterface;
use App\Repositories\Contracts\SoccerMatchTeamRepositoryInterface;
use App\Repositories\Eloquent\PlayerRepositoryEloquent;
use App\Repositories\Eloquent\SoccerMatchRepositoryEloquent;
use App\Repositories\Eloquent\SoccerMatchPlayerRepositoryEloquent;
use App\Repositories\Eloquent\SoccerMatchTeamRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SoccerMatchRepositoryInterface::class, SoccerMatchRepositoryEloquent::class);
        $this->app->bind(PlayerRepositoryInterface::class, PlayerRepositoryEloquent::class);
        $this->app->bind(SoccerMatchPlayerRepositoryInterface::class, SoccerMatchPlayerRepositoryEloquent::class);
        $this->app->bind(SoccerMatchTeamRepositoryInterface::class, SoccerMatchTeamRepositoryEloquent::class);
    }
}
