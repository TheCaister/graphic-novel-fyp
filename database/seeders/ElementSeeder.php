<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Element;
use App\Models\Page;
use App\Models\Series;
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


        // Get a random universe from the database
        // $universe = Universe::inRandomOrder()->first();

        // $element = Element::factory()->create([
        //     'owner_id' => User::inRandomOrder()->first()->id,
        // ]);
        // $universe->elements()->attach($element, [
        //     'elementable_id' => $universe->universe_id,
        // ]);

        // for each universe, create 0-5 elements
        Universe::all()->each(function (Universe $universe) {
            Element::factory()->count(rand(0, 5))->create([
                'owner_id' => User::inRandomOrder()->first()->id,
            ])->each(function (Element $element) use ($universe) {
                $universe->elements()->attach($element, [
                    'elementable_id' => $universe->universe_id,
                ]);
            });
        });

        // for each series, create 0-5 elements
        Series::all()->each(function (Series $series) {
            Element::factory()->count(rand(0, 5))->create([
                'owner_id' => User::inRandomOrder()->first()->id,
            ])->each(function (Element $element) use ($series) {
                $series->elements()->attach($element, [
                    'elementable_id' => $series->series_id,
                ]);
            });
        });

        // for each chapter, create 0-5 elements
        Chapter::all()->each(function (Chapter $chapter) {
            Element::factory()->count(rand(0, 5))->create([
                'owner_id' => User::inRandomOrder()->first()->id,
            ])->each(function (Element $element) use ($chapter) {
                $chapter->elements()->attach($element, [
                    'elementable_id' => $chapter->chapter_id,
                ]);
            });
        });

        // for each page, create 0-5 elements
        Page::all()->each(function (Page $page) {
            Element::factory()->count(rand(0, 5))->create([
                'owner_id' => User::inRandomOrder()->first()->id,
            ])->each(function (Element $element) use ($page) {
                $page->elements()->attach($element, [
                    'elementable_id' => $page->page_id,
                ]);
            });
        });
    }
}
