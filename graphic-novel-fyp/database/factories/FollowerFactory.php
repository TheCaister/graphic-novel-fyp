<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Follower;
use App\Models\User;

class FollowerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Follower::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'follower_id' => User::factory(),
            'followee_id' => User::factory(),
        ];
    }
}