<?php

namespace Database\Seeders;

use App\Models\Series;
use App\Models\Universe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModeratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For every universe, attach 0-2 moderators
        Universe::all()->each(function ($universe) {
            // $universe->moderators()->attach(
            //     User::all()->random(rand(0, 2))->pluck('id')->toArray()
            // );

            User::all()->random(rand(0, 2))->each(function ($user) use ($universe) {
                $universe->moderators()->attach($user->id, [
                    'moderatable_id' => $universe->universe_id,
                ]);
            });
        });

        Series::all()->each(function ($series) {

            User::all()->random(rand(0, 2))->each(function ($user) use ($series) {
                // If the user is already a moderator of the universe that the series belongs to, skip

                // Get the universe that the series belongs to
                // Check if the user is a moderator of that universe
                // If so, skip
                // dd($series->universe);

                if(!$series->universe->moderators()->where('moderator_id', $user->id)->exists()){
                    $series->moderators()->attach($user->id, [
                        'moderatable_id' => $series->series_id,
                    ]);

                }


                
                // if($user->moderatableUniverses()->where('moderatable_id', $series->universe->universe_id)->exists()) {
                //     return;
                // }

                // if ($user->moderates($series->universe)) {
                //     return;
                // }
              
            });
        });

    }
}
