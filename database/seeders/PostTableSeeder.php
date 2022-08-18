<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::truncate();
        Post::create([
            'title' => 'x-wing',
            'team_id' => 1,
            'user_id' => 1
        ]);
        Post::create([
            'title' => 'Tie fighter',
            'team_id' => 1,
            'user_id' => 1
        ]);
        Post::create([
            'title' => 'Star Destroyer',
            'team_id' => 1,
            'user_id' => 1
        ]);
    }
}
