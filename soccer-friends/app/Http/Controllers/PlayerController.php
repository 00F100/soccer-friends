<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PlayerRepositoryInterface;

class PlayerController extends Controller
{
  /**
   * Player Repository
   * @var PlayerRepositoryInterface
   */
  private PlayerRepositoryInterface $playerRepository;

  /**
   * Method for construct instance of PlayerController
   * 
   * @param PlayerRepositoryInterface Player Repository instance
   */
  public function __construct(PlayerRepositoryInterface $playerRepository)
  {
      $this->playerRepository = $playerRepository;
  }
  
  /**
   * Method for list Players page
   * 
   * @param Request HTTP Request instance
   */
  public function index(Request $request)
  {
    $queryParams = $this->getQueryParams($request, 'name', 'asc');
    $players = $this->playerRepository->paginate($queryParams['perPage'], $queryParams['sort'], $queryParams['order']);
    return view('players.index', compact('players', 'queryParams'));
  }

  /**
   * Method for create Player page
   */
  public function create()
  {
    return view('players.form');
  }

  /**
   * Method for edit Player page
   * 
   * @param string Player Id
   */
  public function edit(string $id)
  {
      $player = $this->playerRepository->find($id);
      return view('players.form', compact('player'));
  }

  /**
   * Method for create/update Player
   * 
   * @param Request HTTP Request instance
   * @param string Player Id
   */
  public function store(Request $request, string $id = null)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'level' => 'required|integer|between:1,5',
      'goalkeeper' => 'required|boolean',
    ]);

    if (empty($id))
      $this->playerRepository->create($data);
    else
      $this->playerRepository->update($id, $data);

    return redirect()->route('players.index')->with('success', $id ? 'Player updated!' : 'Player created!');
  }

  /**
   * Method for hard delete Player
   * 
   * @param Request HTTP Request instance
   * @param string Player Id
   */
  public function destroy(Request $request, string $id)
  {
    $queryParams = $this->getQueryParams($request, 'name', 'asc');
    if ($this->playerRepository->delete($id)) {
      return redirect()->route('players.index', $queryParams)->with('success', 'Player deleted successfully.');
    } else {
      return redirect()->route('players.index', $queryParams)->with('error', 'Player cannot be deleted.');
    }
  }

}
