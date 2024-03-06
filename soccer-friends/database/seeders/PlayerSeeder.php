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
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            DB::table('players')->insert([
                'id' => Str::uuid(),
                'name' => $faker->name,
                'level' => $faker->numberBetween(1, 5),
                'goalkeeper' => $faker->boolean($chanceOfGettingTrue = 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
