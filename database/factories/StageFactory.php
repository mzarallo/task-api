<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'order' => $this->faker->numberBetween(0, 100),
            'is_final_stage' => $this->faker->boolean,
            'board_id' => Board::all()->random()->id,
            'author_id' => User::all()->random()->id,
        ];
    }
}
