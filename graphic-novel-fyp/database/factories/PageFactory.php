<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Chapter;
use App\Models\Page;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // 'chapter_id' => Chapter::factory()->create()->chapter_id,
            'page_number' => $this->faker->numberBetween(-100000, 100000),
            'page_image' => $this->faker->word,
        ];
    }
}
