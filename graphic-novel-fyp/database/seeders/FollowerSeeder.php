<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        foreach (User::all() as $user) {
            //   For each user, follow 0-10 other users
            // A user cannot follow themselves
            // A user cannot follow another user more than once
            $numToFollow = rand(0, 10);
            $usersToFollow = User::all()->random($numToFollow);
            foreach ($usersToFollow as $userToFollow) {
                if ($user->id !== $userToFollow->id) {
                    $user->followers()->attach($userToFollow->id);
                }
            }
        }
    }
}
