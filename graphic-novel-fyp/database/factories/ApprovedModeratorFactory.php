<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ApprovedModerator;
use App\Models\User;

class ApprovedModeratorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApprovedModerator::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'approver_id' => User::factory(),
            'approvee_id' => User::factory(),
        ];
    }
}
