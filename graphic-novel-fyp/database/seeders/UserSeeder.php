<?php

namespace Database\Seeders;

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

        // User::factory(10)
        // ->has(Universe::factory()->count(3), 'universes')
        // ->create();

        Universe::factory(10)->create([
            'owner_id' => 1,
        ]);
    }
}
