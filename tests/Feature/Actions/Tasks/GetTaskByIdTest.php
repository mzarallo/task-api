<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Tasks;

use App\Actions\Tasks\GetTaskById;
use App\Data\Services\Tasks\GetTaskByIdServiceDto;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTaskByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_get_task_by_id(): void
    {
        $task = Task::factory()->create();

        $response = GetTaskById::make()->handle(
            GetTaskByIdServiceDto::from([
                'task_id' => $task->id,
            ])
        );

        $this->assertInstanceOf(Task::class, $response);
        $this->assertEquals($task->id, $response->id);
    }

    /**
     * @test
     */
    public function it_get_task_by_id_with_relations_loaded(): void
    {
        $task = Task::factory()->create();

        $response = GetTaskById::make()->handle(
            GetTaskByIdServiceDto::from([
                'task_id' => $task->id,
                'relations' => ['author', 'stage'],
            ])
        );

        $this->assertTrue($response->relationLoaded('author'));
        $this->assertTrue($response->relationLoaded('stage'));
    }
}
