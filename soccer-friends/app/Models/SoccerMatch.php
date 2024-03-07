<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Support\Str;

class SoccerMatch extends Model
{
    protected $table = 'soccer_matches';
    protected $fillable = ['name', 'date', 'positions', 'finished'];

    public function syncPlayers(array $players)
    {
        $this->players()->detach();

        if (count($players) > 0 && !empty($players[0])) {
          $playerIds = explode(',', $players[0]);

          $pivotData = collect($playerIds)->mapWithKeys(function ($playerId) {
            return [$playerId => ['id' => Str::uuid()]];
          });

          $this->players()->attach($pivotData->toArray());
        }
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'soccer_matches_player')
            ->orderBy('goalkeeper', 'desc')
            ->withPivot('confirm');
    }

    public function teams()
    {
        return $this->hasMany(SoccerMatchesTeam::class, 'soccer_match_id')
            ->orderBy('goalkeeper', 'desc')
            ->orderBy('level', 'desc');
    }

    public function soccerMatchesTeam()
    {
        return $this->hasMany(SoccerMatchesTeam::class);
    }
}
