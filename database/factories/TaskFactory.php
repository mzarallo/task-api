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
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(20),
            'description' => $this->faker->realText(350),
            'author_id' => User::factory()->create()->id,
            'stage_id' => Stage::factory()->create()->id,
            'order' => $this->faker->randomNumber(2),
            'tags' => ['UI', 'DEV', 'QA'],
        ];
    }
}
