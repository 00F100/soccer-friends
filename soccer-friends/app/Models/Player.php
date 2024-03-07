<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = ['name', 'level', 'goalkeeper'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function soccerMatches()
    {
        return $this->belongsToMany(SoccerMatch::class, 'soccer_matches_player');
    }

    public function soccerMatchesTeam()
    {
        return $this->hasMany(SoccerMatchesTeam::class);
    }
}
