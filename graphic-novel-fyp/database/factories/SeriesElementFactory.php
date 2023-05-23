<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Element;
use App\Models\Series;
use App\Models\SeriesElement;

class SeriesElementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SeriesElement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'series_id' => Series::factory()->create()->series_id,
            'element_id' => Element::factory()->create()->element_id,
        ];
    }
}
