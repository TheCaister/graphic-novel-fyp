<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Element;
use App\Models\Page;
use App\Models\PagesElement;

class PagesElementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PagesElement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::factory()->create()->page_id,
            'element_id' => Element::factory()->create()->element_id,
        ];
    }
}
