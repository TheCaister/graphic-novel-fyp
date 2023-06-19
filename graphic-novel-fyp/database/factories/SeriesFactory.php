<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Series;
use App\Models\Universe;

class SeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Series::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // 'universe_id' => Universe::factory()->create()->universe_id,
            'series_title' => $this->faker->word,
            'series_genre' => $this->faker->randomElement(['ACTION','ADVENTURE','COMEDY','DRAMA','FANTASY','HORROR','MYSTERY','ROMANCE','THRILLER']),
            'series_summary' => $this->faker->text,
            'series_thumbnail' => $this->faker->word,
            // rating is a random integer between 1 and 5
            // 'rating' => $this->faker->numberBetween(1, 5),
            'rating' => 0,
        ];
    }

}
