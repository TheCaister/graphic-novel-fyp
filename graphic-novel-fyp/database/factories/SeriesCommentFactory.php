<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Series;
use App\Models\SeriesComment;

class SeriesCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SeriesComment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'series_id' => Series::factory()->create()->series_id,
            'comment_id' => Comment::factory()->create()->comment_id,
        ];
    }
}
