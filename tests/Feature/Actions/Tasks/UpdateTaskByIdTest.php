<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Tasks;

use App\Actions\Tasks\UpdateTaskById;
use App\Data\Services\Tasks\UpdateTaskServiceDto;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTaskByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_update_task_by_id(): void
    {
        $task = Task::factory()->create();
        $params = [
            'title' => $this->faker->name,
            'description' => $this->faker->realText,
        ];

        $response = UpdateTaskById::make()->handle(
            $task->id,
            UpdateTaskServiceDto::from($params)
        );

        $this->assertInstanceOf(Task::class, $response);
        $this->assertDatabaseHas('tasks', $params);
    }
}
