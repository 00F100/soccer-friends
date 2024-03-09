<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use App\Repositories\Contracts\SoccerMatchRepositoryInterface;

class SoccerMatchController extends Controller
{
  /**
   * Soccer Match Repository
   * @var SoccerMatchRepositoryInterface
   */
  private SoccerMatchRepositoryInterface $soccerMatchRepository;

  /**
   * Player Repository
   * @var PlayerRepositoryInterface
   */
  private PlayerRepositoryInterface $playerRepository;

  /**
   * Method for construct instance of Soccer Match Controller
   * 
   * @param SoccerMatchRepositoryInterface Soccer Match Repository instance
   * @param PlayerRepositoryInterface Player Repository instance
   */
  public function __construct(
    SoccerMatchRepositoryInterface $soccerMatchRepository,
    PlayerRepositoryInterface $playerRepository
  )
  {
    $this->soccerMatchRepository = $soccerMatchRepository;
    $this->playerRepository = $playerRepository;
  }

  /**
   * Method for list Soccer Match page
   * 
   * @param Request HTTP Request instance
   */
  public function index(Request $request)
  {
    $queryParams = $this->getQueryParams($request, 'date', 'asc');
    $soccerMatches = $this->soccerMatchRepository->paginateWithCountPlayersSelected($queryParams['perPage'], $queryParams['sort'], $queryParams['order']);
    $totalSoccerMatches = $this->playerRepository->count();
    return view('soccer_match.index', compact('soccerMatches', 'totalSoccerMatches'));
  }

  /**
   * Method for create Soccer Match page
   */
  public function create()
  {
    $players = $this->playerRepository->get();
    return view('soccer_match.form', compact('players'));
  }

  /**
   * Method for edit Soccer Match page
   * 
   * @param string Soccer Match Id
   */
  public function edit($id)
  {
    $players = $this->playerRepository->get();
    $soccerMatch = $this->soccerMatchRepository->find($id);
    $selectedPlayers = $this->soccerMatchRepository->getSelectedPlayers($soccerMatch);
    return view('soccer_match.form', compact('players', 'soccerMatch', 'selectedPlayers'));
  }

  /**
   * Method for create/update Soccer Match
   * 
   * @param Request HTTP Request instance
   * @param string Soccer Match Id
   */
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

    if ($this->soccerMatchRepository->save($data, $id)) {
      return redirect()->route('soccer_match.index')->with('success', $id ? 'Soccer Match updated!' : 'Soccer Match created!');
    }
    return back()->with('error', 'Error on try save Soccer Match');
  }

  /**
   * Method for hard delete Soccer Match
   * 
   * @param Request HTTP Request instance
   * @param string Soccer Match Id
   */
  public function destroy(Request $request, $id)
  {
    $queryParams = $this->getQueryParams($request, 'name', 'asc');
    if ($this->soccerMatchRepository->delete($id)) {
      return redirect()->route('soccer_match.index', $queryParams)->with('success', 'Soccer Match deleted successfully.');
    } else {
      return redirect()->route('soccer_match.index', $queryParams)->with('error', 'Soccer Match cannot be deleted.');
    }
  }
}
