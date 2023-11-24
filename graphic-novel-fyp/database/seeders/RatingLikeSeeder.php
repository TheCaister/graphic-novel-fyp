<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For each user, attach 5-10 liked chapters
        User::all()->each(function ($user) {
            $user->likedChapters()->attach(
                Chapter::all()->random(rand(5, 10))->pluck('chapter_id')->toArray()
            );

            // For each user, attach 5-10 rated series, with a rating between 1 and 5

            $random_series = Series::all()->random(rand(0, 5))->pluck('series_id')->toArray();

            for ($i = 0; $i < count($random_series); $i++) {
                $user->ratedSeries()->attach(
                    $random_series[$i],
                    ['rating' => rand(1, 5)]
                );
            }

            // $user->ratedSeries()->sync($random_series, ['rating' => rand(1, 5)]);
        });

        // For each series, update their rating, using values from the user_series_rating table
        Series::all()->each(function ($series) {
            $series->rating = round(DB::table('user_series_rating')->where('series_id', $series->series_id)->avg('rating'), 2);
            $series->save();
        });
    }
}
