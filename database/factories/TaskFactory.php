<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->text(),
            'author_id' => User::all()->random()->id,
            'stage_id' => Stage::all()->random()->id,
            'tags' => \json_encode(['UI', 'DEV', 'QA']),
        ];
    }
}
