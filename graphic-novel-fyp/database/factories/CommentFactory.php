<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\User;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'commenter_id' => User::factory(),
            'replying_to' => Comment::factory()->create()->comment_id,
            'comment_content' => $this->faker->numberBetween(-100000, 100000),
            'created_at' => $this->faker->dateTime(),
        ];
    }
}
