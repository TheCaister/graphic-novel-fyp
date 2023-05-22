<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Universe;
use App\Models\User;

class UniverseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Universe::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'universe_id' => $this->faker->word,
            'owner_id' => User::factory(),
            'universe_name' => $this->faker->word,
        ];
    }
}
