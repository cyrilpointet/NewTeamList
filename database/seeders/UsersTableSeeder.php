<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        for ($i=0; $i<10; $i++) {
            User::create([
                'name' => 'user' . ($i + 1),
                'email' => 'user' . ($i + 1) . '@user.user',
                'password' => Hash::make('user')
            ]);
        }
    }
}
