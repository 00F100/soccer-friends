<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SoccerMatchesPlayer extends Model
{
    protected $table = 'soccer_matches_player';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = ['soccer_match_id', 'player_id', 'confirm'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
