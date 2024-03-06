<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
  public function index(Request $request)
  {
    $perPage = $request->input('perPage', 10);

    $sortField = $request->get('sort', 'level');
    $sortOrder = $request->get('order', 'desc');

    if (!in_array($sortField, ['name', 'level', 'goalkeeper'])) {
        $sortField = 'name';
    }
    if (!in_array($sortOrder, ['asc', 'desc'])) {
        $sortOrder = 'asc';
    }

    $players = Player::orderBy($sortField, $sortOrder)->paginate($perPage);

    return view('players.index', compact('players'));
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
    $page = $request->input('page', 1);
    $sort = $request->input('sort', 'level');
    $order = $request->input('order', 'desc');
    $perPage = $request->input('perPage', 10);

    $player = Player::find($id);
    if ($player) {
      $player->delete();
      return redirect()->route('players.index', compact('page', 'sort', 'order', 'perPage'))->with('success', 'Player deleted successfully.');
    } else {
      return redirect()->route('players.index', compact('page', 'sort', 'order', 'perPage'))->with('error', 'Player not found.');
    }
  }

}
