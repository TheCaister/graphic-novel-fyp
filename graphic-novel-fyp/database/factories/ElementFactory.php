<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Element;
use App\Models\User;

class ElementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Element::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'type' => $this->faker->word,
            'content' => $this->faker->paragraphs(3, true),
            'created_at' => $this->faker->dateTime(),
            'hidden' => $this->faker->boolean,
        ];
    }
}
