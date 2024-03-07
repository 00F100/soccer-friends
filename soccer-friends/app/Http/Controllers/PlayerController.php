<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
  public function index(Request $request)
  {
    $queryParams = $this->getQueryParams($request, 'name', 'asc');
    $players = Player::orderBy($queryParams['sort'], $queryParams['order'])->paginate($queryParams['perPage']);
    return view('players.index', compact('players', 'queryParams'));
  }

  public function create()
  {
    $player = null;
    return view('players.form', compact('player'));
  }

  public function edit($id)
  {
      $player = Player::findOrFail($id);
      return view('players.form', compact('player'));
  }

  public function store(Request $request, $id = null)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'level' => 'required|integer|between:1,5',
      'goalkeeper' => 'required|boolean',
    ]);

    if ($id) {
      $player = Player::findOrFail($id);
      $player->update($request->all());
    } else {
      Player::create($data);
    }

    return redirect()->route('players.index')->with('success', $id ? 'Player updated!' : 'Player created!');
  }

  public function destroy(Request $request, $id)
  {
    $queryParams = $this->getQueryParams($request, 'name', 'asc');

    $player = Player::find($id);

    if ($player) {
      $player->delete();
      return redirect()->route('players.index', $queryParams)->with('success', 'Player deleted successfully.');
    } else {
      return redirect()->route('players.index', $queryParams)->with('error', 'Player not found.');
    }
  }

}
