<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SoccerMatch extends Model
{
    use HasFactory;

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

    public function players()
    {
        return $this->belongsToMany(Player::class, 'soccer_matches_player');
    }

    public function teams()
    {
        return $this->belongsToMany(Player::class, 'soccer_matches_team');
    }
}
