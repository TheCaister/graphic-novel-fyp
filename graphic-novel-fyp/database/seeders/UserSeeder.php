<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Page;
use App\Models\Series;
use App\Models\User;
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

        User::factory(10)
            ->has(Universe::factory(1)
                ->has(Series::factory()
                    ->has(Chapter::factory()
                        ->has(Page::factory(5)))))
            ->create();
    }
}
