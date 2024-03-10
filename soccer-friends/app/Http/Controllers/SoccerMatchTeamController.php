<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\SoccerMatchRepositoryInterface;
use App\Repositories\Contracts\SoccerMatchTeamRepositoryInterface;
use App\Helpers\Contracts\TeamsHelperInterface;

class SoccerMatchTeamController extends Controller
{
  /**
   * Teams Helper
   * @var TeamsHelperInterface
   */
  private TeamsHelperInterface $teamsHelper;

  /**
   * Soccer Match Repository
   * @var SoccerMatchRepositoryInterface
   */
  private SoccerMatchRepositoryInterface $soccerMatchRepository;

  /**
   * Soccer Match Team Repository
   * @var SoccerMatchTeamRepositoryInterface
   */
  private SoccerMatchTeamRepositoryInterface $soccerMatchTeamRepository;

  /**
   * Method for construct instance of Soccer Match Team Controller
   * 
   * @param TeamsHelperInterface Teams Helper
   * @param SoccerMatchRepositoryInterface Soccer Match Repository
   * @param SoccerMatchTeamRepositoryInterface Soccer Match Team
   */
  public function __construct(
    TeamsHelperInterface $teamsHelper,
    SoccerMatchRepositoryInterface $soccerMatchRepository,
    SoccerMatchTeamRepositoryInterface $soccerMatchTeamRepository
  )
  {
    $this->teamsHelper = $teamsHelper;
    $this->soccerMatchRepositoryInterface = $soccerMatchRepository;
    $this->soccerMatchTeamRepository = $soccerMatchTeamRepository;
  }

  /**
   * Method for create teams using Soccer Match
   * 
   * @param string Soccer Match Id
   */
  public function create(string $soccerMatchId)
  {
    try {

      $soccerMatch = $this->soccerMatchRepositoryInterface->getSoccerMatchWithPlayersConfirmed($soccerMatchId);
  
      if(!$soccerMatch) {
          return back()->with('error', 'Soccer Match not found.');
      }
  
      $goalkeepers = $soccerMatch->players->filter(function($player) {
        return $player->goalkeeper == true;
      });
  
      if (count($goalkeepers) < 2) {
        return back()->with('error', 'More goalkeepers are needed to start the match');
        return null;
      }

      if ($soccerMatch->players->count() < $soccerMatch->positions) {
        return back()->with('error', 'More players are needed to start the match');
        return null;
      }
  
      $playersGoalkeeper = $this->teamsHelper->getPlayersByFunction($soccerMatch, true);
      $players = $this->teamsHelper->getPlayersByFunction($soccerMatch, false);
  
      if(!$playersGoalkeeper || count($playersGoalkeeper) == 0 || !$players || count($players) == 0) {
          return back()->with('error', 'Soccer Match Players fail on try find enabled players.');
      }

      $teams = $this->teamsHelper->generate($soccerMatch, $playersGoalkeeper, $players);

      if(!$teams)
        return redirect()->route('welcome.index')->with('error', 'Teams not found, try again.');

      $this->soccerMatchTeamRepository->transaction(function($repository) use ($teams, $soccerMatch, $soccerMatchId) {
        foreach($teams as $side => $team) {
          foreach($team as $player) {
            $repository->create([
              'soccer_match_id' => $soccerMatchId,
              'player_id' => $player->id,
              'side' => $side,
              'level' => $player->level,
              'goalkeeper' => $player->goalkeeper
            ]);
          }
        }
        $soccerMatch->finish();
      });

    } catch (\Exception $e) {
      return redirect()->route('welcome.index')->with('error', 'Error on try generate teams.');
    }

    return redirect()->route('welcome.index')->with('success', 'Soccer Match Team created.');
  }
}
