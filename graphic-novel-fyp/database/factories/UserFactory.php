<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'is_admin' => $this->faker->boolean,
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
            'email' => $this->faker->safeEmail,
            'date_of_birth' => $this->faker->date(),
            'is_banned' => $this->faker->boolean,
            'bio' => $this->faker->text,
            'profile_picture' => $this->faker->word,
            'remember_token' => $this->faker->word,
            'created_at' => $this->faker->dateTime(),
        ];
    }
}
