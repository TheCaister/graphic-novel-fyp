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

            User::all()->random(rand(0, 2))->each(function ($user) use ($universe) {
                $universe->moderators()->attach($user->id, [
                    'moderatable_id' => $universe->universe_id,
                ]);
            });
        });

        Series::all()->each(function ($series) {

            User::all()->random(rand(0, 2))->each(function ($user) use ($series) {

                if(!$series->universe->moderators()->where('moderator_id', $user->id)->exists()){
                    $series->moderators()->attach($user->id, [
                        'moderatable_id' => $series->series_id,
                    ]);

                }
              
            });
        });

    }
}
