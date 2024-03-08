<?php

namespace App\Helpers;

use App\Helpers\Contracts\TeamsHelperInterface;

class TeamsHelper implements TeamsHelperInterface
{
  public function generate(&$soccerMatch)
  {
    $teams = [
      'A' => [],
      'B' => [],
      'R' => []
    ];
    
    $selectedGoalkeeper = $this->drawLevelAndGetPlayers($soccerMatch['enableGoalkeeper']);
    shuffle($selectedGoalkeeper);
    $teams['A'][] = array_shift($selectedGoalkeeper);
    $teams['B'][] = array_shift($selectedGoalkeeper);

    while (
      $this->canAddPlayersToTeam(count($teams['A']), $soccerMatch['soccerMatch']->positions) &&
      $this->canAddPlayersToTeam(count($teams['B']), $soccerMatch['soccerMatch']->positions)
    ) {
      $selectedPlayers = $this->drawLevelAndGetPlayers($soccerMatch['enablePlayers']);
      shuffle($soccerMatch['enablePlayers']);
      $teams['A'][] = array_shift($selectedPlayers);
      $teams['B'][] = array_shift($selectedPlayers);
    }

    if($soccerMatch['enableGoalkeeper']) {
      $teams['R'] = array_merge($teams['R'], $soccerMatch['enableGoalkeeper']);
    }
    if ($soccerMatch['enablePlayers']) {
      $teams['R'] = array_merge($teams['R'], $soccerMatch['enablePlayers']);
    }
    if(
      count($teams['A']) != ($soccerMatch['soccerMatch']->positions / 2) ||
      count($teams['B']) != ($soccerMatch['soccerMatch']->positions / 2)
    ) {
      return null;
    }

    return $teams;
  }

  private function canAddPlayersToTeam($currentTeamSize, $totalPositions) {
      $maxPlayersPerTeam = ($totalPositions / 2);
      return $currentTeamSize < $maxPlayersPerTeam;
  }
  
  private function drawLevelAndGetPlayers(&$enablePlayers, $desiredCount = 2, $factor = 0) {
    $lastLevel = [];
    shuffle($enablePlayers);
    do {
      $level = rand(1, 5);
      if (!in_array($level, $lastLevel)) {
        $lastLevel[] = $level;
        $playersAtLevel = array_filter($enablePlayers, function($player) use ($level, $factor) {
          return $player->level == $level || $player->level == $level + $factor;
        });

        if (count($playersAtLevel) >= $desiredCount) {
          $selectedPlayers = array_slice($playersAtLevel, 0, $desiredCount);
          foreach ($selectedPlayers as $selectedPlayer) {
            if (($key = array_search($selectedPlayer, $enablePlayers)) !== false) {
              unset($enablePlayers[$key]);
            }
          }
          return $selectedPlayers;
        }
      } else {
        if (count($lastLevel) == 5) {
          return $this->drawLevelAndGetPlayers($enablePlayers, $desiredCount = 2, $factor - 1);
        }
      }
    } while (true);
  }
}
