<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoccerMatch;
use App\Models\Player;

class WelcomeController extends Controller
{
  public function index(Request $request)
  {
    $soccerMatch = SoccerMatch::with(['players' => function ($query) {
            $query->orderBy('name', 'asc');
        }])
        ->withCount(['players as players_confirmed_count' => function ($query) {
            $query->where('confirm', true);
            $query->where('goalkeeper', false);
        }])
        ->withCount(['players as players_confirmed_goalkeeper_count' => function ($query) {
            $query->where('confirm', true);
            $query->where('goalkeeper', true);
        }])
        ->where('finished', false)
        ->orderBy('date', 'asc')
        ->first();

    
    $soccerMatchHistories = SoccerMatch::with(['players' => function ($query) {
          $query->orderBy('name', 'asc');
      }])
      ->withCount(['players as players_confirmed_count' => function ($query) {
          $query->where('confirm', true);
          $query->where('goalkeeper', false);
      }])
      ->withCount(['players as players_confirmed_goalkeeper_count' => function ($query) {
          $query->where('confirm', true);
          $query->where('goalkeeper', true);
      }])
      ->where('finished', true)
      ->orderBy('date', 'desc')
      ->get();

    $disableCreateTeam = $soccerMatch->players_confirmed_count < ($soccerMatch->positions - 2) || $soccerMatch->players_confirmed_goalkeeper_count < 2;

    return view('welcome', compact('soccerMatch', 'disableCreateTeam', 'soccerMatchHistories'));
  }

}
