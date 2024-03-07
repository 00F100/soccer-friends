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
        $names = [
            "A Batalha dos Gigantes",
            "O Clássico Eterno",
            "Duelo dos Invencíveis",
            "A Guerra das Estrelas",
            "O Desafio dos Campeões",
            "A Lenda das Copas",
            "O Confronto dos Sonhos",
            "A Revanche do Século",
            "O Duelo de Titãs",
            "A Saga dos Rivais",
            "O Encontro dos Mestres",
            "A Dança dos Campeões",
            "O Choque dos Líderes",
            "A Jornada dos Valentes",
            "O Épico do Futebol",
            "A Prova dos Reis",
            "O embate dos Gladiadores",
            "A Noite das Lendas",
            "O Palco dos Sonhos",
            "A Arena dos Valentes",
            "O Desfile dos Campeões",
            "A Corrida ao Título",
            "O Combate das Feras",
            "A Batalha pela Glória",
            "O Teste dos Gigantes",
            "A Disputa dos Eternos",
            "O Jogo dos Jogos",
            "A Partida Imortal",
            "O Conflito dos Astros",
            "A Peleja dos Poderosos",
            "O Tira-Teima dos Rivais",
            "A Final dos Sonhos",
            "O Clássico dos Clássicos",
            "A Disputa Infinita",
            "O Duelo dos Destinos",
            "A Guerra pela Honra",
            "O Enfrentamento dos Ícones",
            "A Luta pelo Trono",
            "O Desafio Supremo",
            "A Batalha dos Desafiantes",
            "O Confronto das Dinastias",
            "A Revolta dos Underdogs",
            "O Enigma do Futebol",
            "A Batalha dos Semideuses",
            "O Dia D do Futebol",
            "A Ascensão dos Heróis",
            "O Crepúsculo dos Ídolos",
            "A Tempestade no Estádio",
            "O Renascimento dos Clássicos",
            "A Odisseia no Gramado",
        ];
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
        
            $positions = 2 * $faker->numberBetween(3, 10);
            $date = $faker->dateTimeBetween('-30 days', '+30 days');
            $soccerMatchId = Str::uuid();

            DB::table('soccer_matches')->insert([
                'id' => $soccerMatchId,
                'name' => array_shift($names),
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
                    'id' => Str::uuid(),
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
