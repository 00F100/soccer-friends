<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SoccerMatchesTeam extends Model
{
    protected $table = 'soccer_matches_team';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = ['soccer_match_id', 'player_id', 'side', 'level', 'goalkeeper'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
