<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Chapter;
use App\Models\ChaptersElement;
use App\Models\Element;

class ChaptersElementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChaptersElement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'chapter_id' => Chapter::factory()->create()->chapter_id,
            'element_id' => Element::factory()->create()->element_id,
        ];
    }
}
