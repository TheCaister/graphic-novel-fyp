<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Chapter;
use App\Models\ChaptersComment;
use App\Models\Comment;

class ChaptersCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChaptersComment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'chapters_comments_id' => $this->faker->word,
            'chapter_id' => Chapter::factory()->create()->chapter_id,
            'comment_id' => Comment::factory()->create()->comment_id,
        ];
    }
}
