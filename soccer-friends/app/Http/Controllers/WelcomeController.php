<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoccerMatch;
use App\Models\Player;

class WelcomeController extends Controller
{
  public function index(Request $request)
  {
    $soccerMatch = SoccerMatch::where('finished', false)
                              ->where('date', '>', now())
                              ->orderBy('date', 'asc')
                              ->first();

    $selectedPlayersGoalkeepers = $soccerMatch->players()->where('goalkeeper', true)->orderBy('name', 'asc')->pluck('id')->toArray();
    $selectedPlayersField = $soccerMatch->players()->where('goalkeeper', false)->orderBy('name', 'asc')->pluck('id')->toArray();
    $selectedPlayers = array_merge($selectedPlayersGoalkeepers, $selectedPlayersField);

    $players = Player::whereIn('id', $selectedPlayers)
                     ->with(['soccerMatches' => function($query) use ($soccerMatch) {
                         $query->where('soccer_match_id', $soccerMatch->id)
                               ->select('id', 'player_id', 'confirm');
                     }])
                     ->get();
    
    return view('welcome', compact('soccerMatch', 'players'));
  }

}
