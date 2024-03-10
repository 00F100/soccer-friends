<?php

namespace App\Helpers;

use App\Models\SoccerMatch;
use Illuminate\Support\Facades\Log;
use App\Helpers\Contracts\TeamsHelperInterface;

class TeamsHelper implements TeamsHelperInterface
{
  /**
   * Method for get players by function
   * 
   * @param SoccerMatch Soccer Match instance
   * @param bool is Goalkeeper (function)
   */
  public function getPlayersByFunction(SoccerMatch $soccerMatch, bool $goalkeeper)
  {
    $enablePlayers = [];

    foreach($soccerMatch->players as $player) {
      if ($goalkeeper) {
        if ($player->goalkeeper) {
            $enablePlayers[] = $player;
        }
      } else {
        if (!$player->goalkeeper) {
            $enablePlayers[] = $player;
        }
      }
    }
    return $enablePlayers;
  }

  /**
   * Method for generate teams using Players into Soccer Match
   * 
   * @param SoccerMatch Soocer Match instance
   * @param array Players Goalkeeper
   * @param array Players non Goalkeeper
   */
  public function generate(SoccerMatch $soccerMatch, $playersGoalkeeper, $players)
  {
    $teams = [
      'A' => [],
      'B' => [],
      'R' => []
    ];
    
    $selectedGoalkeeper = $this->drawLevelAndGetPlayers($playersGoalkeeper);
    shuffle($selectedGoalkeeper);
    $teams['A'][] = array_shift($selectedGoalkeeper);
    $teams['B'][] = array_shift($selectedGoalkeeper);

    while (
      $this->canAddPlayersToTeam(count($teams['A']), $soccerMatch->positions) &&
      $this->canAddPlayersToTeam(count($teams['B']), $soccerMatch->positions)
    ) {
      $selectedPlayers = $this->drawLevelAndGetPlayers($players);
      shuffle($players);
      $teams['A'][] = array_shift($selectedPlayers);
      $teams['B'][] = array_shift($selectedPlayers);
    }

    if($playersGoalkeeper) {
      $teams['R'] = array_merge($teams['R'], $playersGoalkeeper);
    }
    if ($players) {
      $teams['R'] = array_merge($teams['R'], $players);
    }
    if(
      count($teams['A']) != ($soccerMatch->positions / 2) ||
      count($teams['B']) != ($soccerMatch->positions / 2)
    ) {
      return null;
    }

    return $teams;
  }

  /**
   * Method for test if team can add Players
   * 
   * @param int Current team size
   * @param int Total positions of team
   */
  private function canAddPlayersToTeam(int $currentTeamSize, int $totalPositions) {
      $maxPlayersPerTeam = ($totalPositions / 2);
      return $currentTeamSize < $maxPlayersPerTeam;
  }
  
  /**
   * Method for creating teams in a balanced way
   * 
   * @param array Players
   * @param int Number of players to find and match
   * @param int Factor for find and match players if not match in same level
   */
  private function drawLevelAndGetPlayers(&$players, int $desiredCount = 2, int $factor = 0) {
    $lastLevel = [];
    shuffle($players);
    do {
      $level = rand(1, 5);
      if (!in_array($level, $lastLevel)) {
        $lastLevel[] = $level;
        $playersAtLevel = array_filter($players, function($player) use ($level, $factor) {
          return $player->level == $level || $player->level == $level + $factor;
        });

        if (count($playersAtLevel) >= $desiredCount) {
          $selectedPlayers = array_slice($playersAtLevel, 0, $desiredCount);
          foreach ($selectedPlayers as $selectedPlayer) {
            if (($key = array_search($selectedPlayer, $players)) !== false) {
              unset($players[$key]);
            }
          }
          return $selectedPlayers;
        }
      } else {
        if (count($lastLevel) == 5) {
          return $this->drawLevelAndGetPlayers($players, $desiredCount = 2, $factor - 1);
        }
      }
    } while (true);
  }
}
