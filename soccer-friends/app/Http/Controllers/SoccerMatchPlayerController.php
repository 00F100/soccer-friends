<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\SoccerMatchPlayerRepositoryInterface;

class SoccerMatchPlayerController extends Controller
{
  /**
   * Soccer Match Player Repository
   * @var SoccerMatchPlayerRepositoryInterface
   */
  private SoccerMatchPlayerRepositoryInterface $soccerMatchPlayerRepositoryInterface;

  /**
   * Method for construct instance of Soccer Match Player Controller
   * 
   * @param SoccerMatchPlayerRepositoryInterface Soccer Match Player Repository instance
   */
  public function __construct(
    SoccerMatchPlayerRepositoryInterface $soccerMatchPlayerRepositoryInterface
  )
  {
    $this->soccerMatchPlayerRepositoryInterface = $soccerMatchPlayerRepositoryInterface;
  }

  /**
   * Method for confirm Player into Soccer Match
   * 
   * @param string Soccer Match Id
   * @param string Player Id
   */
  public function confirm(string $soccerMatchId, string $playerId)
  {
    if ($this->soccerMatchPlayerRepositoryInterface->confirm($soccerMatchId, $playerId)) {
      return back()->with('success', 'Player Confirmed');
    } else {
      return back()->with('error', 'Soccer Match Player not found.');
    }
  }
}
