<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'author_id' => User::factory()->create()->id,
            'reply_to_comment_id' => null,
            'task_id' => Task::factory()->create()->id,
        ];
    }
}
