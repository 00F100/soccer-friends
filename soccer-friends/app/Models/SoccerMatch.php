<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SoccerMatch extends Model
{
    use HasFactory;

    protected $table = 'soccer_matches';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = ['name', 'date', 'positions', 'finished'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

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
