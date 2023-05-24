<?php

namespace Database\Factories;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Series;
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
        // Calling the commentable method will return either a post or a chapter.
        // $commentable = $this->commentable();

        // dd($commentable);

        return [
            // 'commenter_id' => User::factory(),

            // 'replying_to' => Comment::factory()->create()->comment_id,
            'comment_content' => $this->faker->text,
            'created_at' => $this->faker->dateTime(),
            // 'commentable_id' => $commentable->id,
            // 'commentable_type' => $commentable,
        ];
    }

    // public function commentable()
    // {
    //     return $this->faker->randomElement([
    //         Series::class,
    //         Chapter::class,
    //     ]);
    // }
}
