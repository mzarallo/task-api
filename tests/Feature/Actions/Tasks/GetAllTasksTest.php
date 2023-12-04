<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Tasks;

use App\Actions\Tasks\GetAllTasks;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetAllTasksTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_tasks_paginated(): void
    {
        Task::factory()->count(2)->create();

        $response = GetAllTasks::make()->handle(
            GetAllTaskServiceDto::from([
                'relations' => [],
                'paginated' => true,
            ])
        );

        $this->assertCount(2, $response->items());
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertContainsOnlyInstancesOf(Task::class, $response->items());
    }

    /**
     * @test
     */
    public function it_get_tasks_as_collection(): void
    {
        Task::factory()->count(2)->create();

        $response = GetAllTasks::make()->handle(
            GetAllTaskServiceDto::from([
                'relations' => [],
                'paginated' => false,
            ])
        );

        $this->assertCount(2, $response);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(Task::class, $response);
    }

    /**
     * @test
     */
    public function it_get_tasks_with_relations_loaded(): void
    {
        Task::factory()->count(2)->create();

        $response = GetAllTasks::make()->handle(
            GetAllTaskServiceDto::from([
                'relations' => ['author', 'stage'],
                'paginated' => true,
            ])
        );

        $this->assertTrue($response->offsetGet(0)->relationLoaded('author'));
        $this->assertTrue($response->offsetGet(0)->relationLoaded('stage'));
    }
}
