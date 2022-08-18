<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::truncate();
        $team_1 = Team::create([
            'name' => 'testGroupNoToto 1'
        ]);
        $team_1->members()->attach(1, ['admin' => true]);
        $team_1->members()->attach(3, ['admin' => true]);
        $team_1->members()->attach(2);
        $team_1->members()->attach(4);
        $team_1->save();

        $team_2 = Team::create([
            'name' => 'testGroupNoToto 2'
        ]);
        $team_2->members()->attach(2, ['admin' => true]);
        $team_2->members()->attach(1);
        $team_2->members()->attach(3);
        $team_2->save();

        $team_3 = Team::create([
            'name' => 'testGroupNoToto 3'
        ]);
        $team_3->members()->attach(7, ['admin' => true]);
        $team_3->members()->attach(8);
        $team_3->save();

        $team_4 = Team::create([
            'name' => 'testGroupNoToto 4'
        ]);
        $team_4->members()->attach(7, ['admin' => true]);
        $team_4->members()->attach(8);
        $team_4->save();
    }
}
