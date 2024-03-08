<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\SoccerMatchRepositoryInterface;

class WelcomeController extends Controller
{
  /**
   * Soccer Match Repository
   * @var SoccerMatchRepositoryInterface
   */
  private SoccerMatchRepositoryInterface $soccerMatchRepositoryInterface;

  /**
   * Method for construct instance of WelcomeController
   * 
   * @param SoccerMatchRepositoryInterface Soccer Match Repository instance
   */
  public function __construct(
    SoccerMatchRepositoryInterface $soccerMatchRepositoryInterface
  )
  {
    $this->soccerMatchRepositoryInterface = $soccerMatchRepositoryInterface;
  }
  
  /**
   * Method for Welcome Page
   */
  public function index()
  {
    $soccerMatch = $this->soccerMatchRepositoryInterface->getNextMatch();
    $soccerMatchHistories = $this->soccerMatchRepositoryInterface->getHistoryMatches();
    $disableCreateTeam = $soccerMatch->players_confirmed_count < ($soccerMatch->positions - 2) || $soccerMatch->players_confirmed_goalkeeper_count < 2;
    return view('welcome', compact('soccerMatch', 'disableCreateTeam', 'soccerMatchHistories'));
  }
}
