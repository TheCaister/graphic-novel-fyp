<?php

namespace Database\Seeders;

use App\Models\Comment;
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

        // Create 10 comments created by a random user
        Comment::factory(50)->
        sequence(
            fn ($sequence) => ['commenter_id' => User::inRandomOrder()->first()->id]
        )->create();

        // Create 10 replies to a random comment
        Comment::factory(100)->
        sequence(
            fn ($sequence) => [
                'commenter_id' => User::inRandomOrder()->first()->id,
                'replying_to' => Comment::inRandomOrder()->first()->comment_id,]
        )->create();
    }
}
