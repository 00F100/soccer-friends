<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SoccerMatchSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
        
            $positions = 2 * $faker->numberBetween(3, 10);
            $date = $faker->dateTimeBetween('-30 days', '+30 days');
            $soccerMatchId = Str::uuid();

            DB::table('soccer_matches')->insert([
                'id' => $soccerMatchId,
                'name' => $faker->sentence(3),
                'positions' => $positions,
                'date' => $date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $goalkeepersIds = DB::table('players')
                            ->where('goalkeeper', true)
                            ->inRandomOrder()
                            ->take(2)
                            ->pluck('id');

            $fieldPlayersCount = $positions - 2;

            $fieldPlayersIds = DB::table('players')
                                ->where('goalkeeper', false)
                                ->inRandomOrder()
                                ->take($fieldPlayersCount)
                                ->pluck('id');

            $playerIds = $goalkeepersIds->merge($fieldPlayersIds);

            $soccerMatchPlayers = $playerIds->map(function ($playerId) use ($soccerMatchId) {
                return [
                    'soccer_match_id' => $soccerMatchId,
                    'player_id' => $playerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('soccer_matches_player')->insert($soccerMatchPlayers);
        }
    }
}
