<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChaptersComment;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // For every chapter, create 5-10 comments
        Chapter::all()->each(function ($chapter) {
            Comment::factory(rand(0, 5))
                ->sequence(
                    fn ($sequence) => ['commenter_id' => User::inRandomOrder()->first()->id]
                )
                ->create([
                    'commentable_id' => $chapter->chapter_id,
                    'commentable_type' => Chapter::class,
                ]);
        });

        // For every series, create 5-10 comments
        Series::all()->each(function ($series) {
            Comment::factory(rand(0, 5))
                ->sequence(
                    fn ($sequence) => ['commenter_id' => User::inRandomOrder()->first()->id]
                )
                ->create([
                    'commentable_id' => $series->series_id,
                    'commentable_type' => Series::class,
                ]);
        });

        // For every comment, create 0-5 replies
        Comment::all()->each(function ($comment) {
            Comment::factory(rand(0, 5))
                ->sequence(
                    fn ($sequence) => [
                        'commenter_id' => User::inRandomOrder()->first()->id,
                        'replying_to' => $comment->comment_id,
                    ]
                )
                ->create([
                    'commentable_id' => $comment->commentable_id,
                    'commentable_type' => $comment->commentable_type,
                ]);
        });
    }
}
