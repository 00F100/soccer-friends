<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoccerMatch;
use App\Models\Player;

class SoccerMatchController extends Controller
{
    public function index(Request $request)
    {
      $perPage = $request->input('perPage', 10);

      $sortField = $request->get('sort', 'date');
      $sortOrder = $request->get('order', 'asc');

      if (!in_array($sortField, ['name', 'positions', 'date'])) {
          $sortField = 'name';
      }
      if (!in_array($sortOrder, ['asc', 'desc'])) {
          $sortOrder = 'asc';
      }

      $soccer_matches = SoccerMatch::withCount('players as players_selected')
        ->orderBy($sortField, $sortOrder)
        ->paginate($perPage);
      
      return view('soccer_match.index', compact('soccer_matches'));
    }

    public function create()
    {
      $soccer_match = null;
      $selectedPlayers = [];
      $goalkeepers = Player::where('goalkeeper', true)->orderBy('name', 'asc')->get();
      $fieldPlayers = Player::where('goalkeeper', false)->orderBy('name', 'asc')->get();
      $players = $goalkeepers->merge($fieldPlayers);
      
      return view('soccer_match.form', compact('soccer_match', 'players', 'selectedPlayers'));
    }

    public function edit($id)
    {
      $soccer_match = SoccerMatch::findOrFail($id);
      
      $goalkeepers = Player::where('goalkeeper', true)->orderBy('name', 'asc')->get();
      $fieldPlayers = Player::where('goalkeeper', false)->orderBy('name', 'asc')->get();
      $players = $goalkeepers->merge($fieldPlayers);
      
      $selectedPlayersGoalkeepers = $soccer_match->players()->where('goalkeeper', true)->orderBy('name', 'asc')->pluck('id')->toArray();
      $selectedPlayersField = $soccer_match->players()->where('goalkeeper', false)->orderBy('name', 'asc')->pluck('id')->toArray();
      $selectedPlayers = array_merge($selectedPlayersGoalkeepers, $selectedPlayersField);

      return view('soccer_match.form', compact('soccer_match', 'players', 'selectedPlayers'));
    }

    public function confirm($soccerMatchId, $playerId)
    {
      echo '<pre>';
      print_r([$soccerMatchId, $playerId]);
      die;
    }


    public function store(Request $request, $id = null)
    {
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

        if (count($request->players) > $request->positions) {
            return back()->withErrors(['players' => 'The total number of selected players cannot exceed the positions available.'])->withInput();
        }


        if ($id) {
          $soccerMatch = SoccerMatch::findOrFail($id);
          $soccerMatch->update($request->only(['name', 'date', 'positions']));
        } else {
          $soccerMatch = SoccerMatch::create($data);
        }

        if (count($request->players) > 0) {
          $playerIds = explode(',', $request->players[0]);
          if (!in_array("", $playerIds)) {
            $soccerMatch->players()->sync($playerIds);
          } else {
            $soccerMatch->players()->sync([]);
          }
      }

        return redirect()->route('soccer_match.index')->with('success', $id ? 'Soccer Match updated!' : 'Soccer Match created!');
    }

    public function destroy(Request $request, $id)
    {
      $page = $request->input('page', 1);
      $sort = $request->input('sort', 'date');
      $order = $request->input('order', 'asc');
      $perPage = $request->input('perPage', 10);

      $soccer_match = SoccerMatch::find($id);
      if ($soccer_match) {
        $soccer_match->delete();
        return redirect()->route('soccer_match.index', compact('page', 'sort', 'order', 'perPage'))->with('success', 'Soccer Match deleted successfully.');
      } else {
        return redirect()->route('soccer_match.index', compact('page', 'sort', 'order', 'perPage'))->with('error', 'Soccer Match not found.');
      }
    }
}
