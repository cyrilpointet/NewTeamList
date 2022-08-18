<?php

namespace Database\Seeders;

use App\Models\Invitation;
use Illuminate\Database\Seeder;

class InvitationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Invitation::truncate();
        Invitation::create([
            'team_id' => 3,
            'user_email' => 'user1@user.user',
            'is_from_team' => true
        ]);

        Invitation::create([
            'team_id' => 1,
            'user_email' => 'user5@user.user',
            'is_from_team' => false
        ]);

        Invitation::create([
            'team_id' => 2,
            'user_email' => 'user1@user.user',
            'is_from_team' => false
        ]);
    }
}
