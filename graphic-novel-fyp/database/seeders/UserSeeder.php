<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Page;
use App\Models\Series;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Universe;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // UserSeeder::class;
        User::factory()->create([
            'username' => 'Test User',
            // Hash the password
            'password' => bcrypt('password'),
            'email' => 'test@test.com',
            'is_admin' => true,
        ]);

        User::factory(50)->create();

        User::factory(10)
            ->has(Universe::factory(1)
                ->has(Series::factory()
                    // ->has(Comment::factory(3)
                    //     ->sequence(
                    //         fn ($sequence) => ['commenter_id' => User::inRandomOrder()->first()->id]
                    //     )
                    //     ->hasReplies(3))
                    // For every chapter, create 5 pages, with the page_number starting at 1, incrementing by 1 until 5

                    ->has(Chapter::factory()
                        ->has(
                            Page::factory(5)
                                // Using this sequence, we can create 5 pages, with the page_number starting at 1, incrementing by 1 until 5
                                ->sequence(
                                    fn ($sequence) => ['page_number' => ($sequence->index % 5) + 1]
                                )
                        )
                        // ->has(Comment::factory(3)
                        //     ->sequence(
                        //         fn ($sequence) => ['commenter_id' => User::inRandomOrder()->first()->id]
                        //     ))
                            )))
            ->create();
    }
}
