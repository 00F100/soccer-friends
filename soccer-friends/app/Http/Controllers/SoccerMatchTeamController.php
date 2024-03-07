<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoccerMatch;
use App\Models\Player;
use App\Models\SoccerMatchesPlayer;
use App\Models\SoccerMatchesTeam;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SoccerMatchTeamController extends Controller
{
    public function create(Request $request, $soccerMatchId = null)
    {
        DB::beginTransaction();
        
        try {
            $soccerMatch = SoccerMatch::with(['players' => function ($query) {
                $query->orderBy('name', 'asc');
                $query->where('confirm', true);
            }])
            ->where('id', $soccerMatchId)
            ->first();
    
            if(!$soccerMatch) {
                return redirect()->route('welcome.index')->with('error', 'Soccer Match not found.');
            }
    
            if ($soccerMatch->players->count() < $soccerMatch->positions) {
                return redirect()->route('welcome.index')->with('error', 'Minimal players not found.');
            }
    
            $goalkeepers = $soccerMatch->players->filter(function($player) {
                return $player->goalkeeper == true;
            });
    
            if (count($goalkeepers) < 2) {
                return redirect()->route('welcome.index')->with('error', 'Minimal Goalkeepers not found.');
            }
    
            $enableGoalkeeper = [];
            $enablePlayers = [];
    
            foreach($soccerMatch->players as $player) {
                if ($player->pivot->confirm) {
                    if ($player->goalkeeper) {
                        $enableGoalkeeper[] = $player;
                    } else {
                        $enablePlayers[] = $player;
                    }
                }
            }
    
            $teams = [
                'A' => [],
                'B' => [],
                'R' => []
            ];
            
            $selectedGoalkeeper = $this->drawLevelAndGetPlayers($enableGoalkeeper);
            shuffle($selectedGoalkeeper);
            $teams['A'][] = array_shift($selectedGoalkeeper);
            $teams['B'][] = array_shift($selectedGoalkeeper);
    
            while ($this->canAddPlayersToTeam(count($teams['A']), $soccerMatch->positions) && $this->canAddPlayersToTeam(count($teams['B']), $soccerMatch->positions)) {
                $selectedPlayers = $this->drawLevelAndGetPlayers($enablePlayers);
                shuffle($enablePlayers);
                $teams['A'][] = array_shift($selectedPlayers);
                $teams['B'][] = array_shift($selectedPlayers);
            }
    
            if($enableGoalkeeper) {
                $teams['R'] = array_merge($teams['R'], $enableGoalkeeper);
            }
            if ($enablePlayers) {
                $teams['R'] = array_merge($teams['R'], $enablePlayers);
            }
    
            if(
                count($teams['A']) != ($soccerMatch->positions / 2) ||
                count($teams['B']) != ($soccerMatch->positions / 2)
            ) {
                return redirect()->route('welcome.index')->with('error', 'Error on try mount team.');
            }
    
            foreach($teams as $side => $team) {
                foreach($team as $player) {
                    SoccerMatchesTeam::create([
                        'soccer_match_id' => $soccerMatch->id,
                        'player_id' => $player->id,
                        'side' => $side,
                        'level' => $player->level,
                        'goalkeeper' => $player->goalkeeper
                    ]);
                }
            }
    
            $soccerMatch->finished = true;
            $soccerMatch->save();
            
            DB::commit();
            
            return redirect()->route('welcome.index')->with('success', 'Soccer Match Team created.');
        } catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }
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
