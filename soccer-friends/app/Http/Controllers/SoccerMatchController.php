<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoccerMatch;
use App\Models\Player;
use App\Models\SoccerMatchesPlayer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SoccerMatchController extends Controller
{
    public function index(Request $request)
    {
      $queryParams = $this->getQueryParams($request, 'name', 'asc');

      $soccerMatches = SoccerMatch::withCount('players as players_selected')
        ->orderBy($queryParams['sort'], $queryParams['order'])
        ->paginate($queryParams['perPage']);
      
      return view('soccer_match.index', compact('soccerMatches'));
    }

    public function create()
    {
      $players = Player::orderBy('goalkeeper', 'desc')->orderBy('name', 'asc')->get();
      return view('soccer_match.form', compact('players'));
    }

    public function edit($id)
    {
      $soccerMatch = SoccerMatch::findOrFail($id);
      $players = Player::orderBy('goalkeeper', 'desc')->orderBy('name', 'asc')->get();
      
      $selectedPlayers = $soccerMatch
        ->players()
        ->orderBy('players.goalkeeper', 'desc')
        ->orderBy('players.name', 'asc')
        ->pluck('players.id')
        ->toArray();

      return view('soccer_match.form', compact('soccerMatch', 'players', 'selectedPlayers'));
    }

    public function confirm($soccerMatchId, $playerId)
    {
      $matchPlayer = SoccerMatchesPlayer::where('soccer_match_id', $soccerMatchId)
                                     ->where('player_id', $playerId)
                                     ->first();

      if ($matchPlayer) {
        $matchPlayer->confirm = true;
        $matchPlayer->save();
        return back()->with('success', 'Player Confirmed');
      } else {
        return back()->with('error', 'Soccer Match Player not found.');
      }
    }

    public function store(Request $request, $id = null)
    {
      DB::beginTransaction();
      try {
        $data = $request->validate([
          'name' => 'required|string|max:255',
          'date' => 'required|date',
          'positions' => [
            'required',
            'integer',
            'min:6',
            'max:20',
            function($attribute, $value, $fail) {
              if ($value % 2 !== 0) {
                $fail($attribute.' must be an even number.');
              }
            },
          ],
          'players' => 'array'
        ]);
  
        $soccerMatch = SoccerMatch::updateOrCreate(
          compact('id'),
          $data
        );
  
        $soccerMatch->syncPlayers($request->players);

        DB::commit();
  
        return redirect()->route('soccer_match.index')->with('success', $id ? 'Soccer Match updated!' : 'Soccer Match created!');
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }
    }

    public function destroy(Request $request, $id)
    {
      $queryParams = $this->getQueryParams($request, 'date', 'asc');

      $soccer_match = SoccerMatch::find($id);
      if ($soccer_match) {
        $soccer_match->delete();
        return redirect()->route('soccer_match.index', $queryParams)->with('success', 'Soccer Match deleted successfully.');
      } else {
        return redirect()->route('soccer_match.index', $queryParams)->with('error', 'Soccer Match not found.');
      }
    }
}
