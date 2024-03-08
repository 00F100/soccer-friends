<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    public function run()
    {
        $goalkeepers = [
            "Alisson - Liverpool",
            "Ederson - Manchester City",
            "Marcos - Palmeiras",
            "Dida - Milan",
            "Taffarel - Grêmio",
            "Júlio César - Flamengo",
            "Cláudio Taffarel - Grêmio",
            "Gilmar - Santos",
            "Leão - Palmeiras",
            "Zetti - São Paulo",
            "Rogério Ceni - São Paulo",
            "Carlos - Atlético Mineiro",
            "Jefferson - Botafogo",
            "Victor - Atlético Mineiro",
            "Fábio - Cruzeiro",
            "Danrlei - Grêmio",
            "Márcio - A. Goianiense",
            "Waldir Peres - São Paulo",
            "Émerson Leão - Palmeiras",
            "Castilho - Fluminense",
        ];

        $players = [
            "Pelé - Santos",
            "Zico - Flamengo",
            "Romário - Vasco da Gama",
            "Ronaldo Nazário - Inter de Milão",
            "Rivaldo - Barcelona",
            "Ronaldinho Gaúcho - Barcelona",
            "Cafu - São Paulo",
            "Roberto Carlos - Real Madrid",
            "Sócrates - Corinthians",
            "Tostão - Cruzeiro",
            "Garrincha - Botafogo",
            "Zé Roberto - Palmeiras",
            "Djalma Santos - Palmeiras",
            "Nilton Santos - Botafogo",
            "Jairzinho - Botafogo",
            "Carlos Alberto Torres - Santos",
            "Falcão - Internacional",
            "Kaká - Milan",
            "Neymar Jr. - Santos",
            "Bebeto - Flamengo",
            "Aldair - Roma",
            "Gilmar - Santos",
            "Clodoaldo - Santos",
            "Gérson - Flamengo",
            "Leônidas da Silva - Flamengo",
            "Raí - São Paulo",
            "Rivelino - Corinthians",
            "Mário Zagallo - Botafogo",
            "Taffarel - Grêmio",
            "Júlio César - Flamengo",
            "Ademir da Guia - Palmeiras",
            "Zetti - São Paulo",
            "Éder - Atlético Mineiro",
            "Dirceu - Vasco da Gama",
            "Leonardo - São Paulo",
            "Júnior - Flamengo",
            "Vavá - Vasco da Gama",
            "Careca - São Paulo",
            "Hulk - Porto",
            "Oscar - São Paulo",
            "Danilo - Corinthians",
            "Lucio - Bayer Leverkusen",
            "Mauro Silva - Deportivo La Coruña",
            "Thiago Silva - Paris Saint-Germain",
            "David Luiz - Chelsea",
            "Fred - Fluminense",
            "Paulinho - Corinthians",
            "Robinho - Santos",
            "Dida - Milan",
            "Émerson Leão - Palmeiras",
            "Castilho - Fluminense",
        ];

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $isGoalkeeper = $faker->boolean($chanceOfGettingTrue = 20);
            DB::table('players')->insert([
                'id' => Str::uuid(),
                'name' => $isGoalkeeper ? array_shift($goalkeepers) : array_shift($players),
                'level' => $faker->numberBetween(1, 5),
                'goalkeeper' => $isGoalkeeper,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
