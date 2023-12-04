<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Tasks;

use App\Actions\Tasks\DeleteTaskById;
use App\Data\Services\Tasks\DeleteTaskByIdServiceDto;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTasksByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_delete_a_task(): void
    {
        $task = Task::factory()->create();

        $response = DeleteTaskById::make()->handle(
            DeleteTaskByIdServiceDto::from([
                'task_id' => $task->id,
            ])
        );

        $this->assertTrue($response);
        $this->assertDatabaseEmpty('tasks');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_task_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        DeleteTaskById::make()->handle(
            DeleteTaskByIdServiceDto::from([
                'task_id' => $this->faker->randomNumber(),
            ])
        );
    }
}
