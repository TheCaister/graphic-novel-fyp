<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Chapter;
use App\Models\Series;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // 'series_id' => Series::factory()->create()->series_id,
            'chapter_number' => $this->faker->numberBetween(-10000, 10000),
            'chapter_title' => $this->faker->word,
            'chapter_thumbnail' => $this->faker->word,
            'chapter_notes' => $this->faker->text,
            'scheduled_publish' => $this->faker->dateTime(),
        ];
    }
}
