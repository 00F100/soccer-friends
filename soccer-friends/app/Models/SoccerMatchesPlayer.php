<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Support\Str;

class SoccerMatchesPlayer extends Model
{
    protected $table = 'soccer_matches_player';
    protected $fillable = ['soccer_match_id', 'player_id', 'confirm'];

    public function confirm() {
      $this->confirm = true;
      $this->save();
    }
}
