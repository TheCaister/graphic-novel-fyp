<?php

namespace Database\Seeders;

use App\Models\Element;
use App\Models\Universe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // For every universe, create 1-5 elements
        Universe::all()->each(function ($universe) {
            $universe->elements()->attach(
                Element::factory(rand(1, 5))->create([
                    'owner_id' => User::inRandomOrder()->first()->id,
                ])
            );
        });
    }
}
