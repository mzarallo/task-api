<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\GetAllStages;
use App\Data\Services\Stages\GetAllStagesServiceDto;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GetAllStagesTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_stages_paginated(): void
    {
        Notification::fake();
        $board = Board::factory()->create();
        Stage::factory()->state(['board_id' => $board->id])->count(2)->create();

        $response = GetAllStages::make()->handle(
            GetAllStagesServiceDto::from([
                'board_id' => $board->id,
                'paginated' => true,
            ])
        );

        $this->assertCount(2, $response->items());
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertContainsOnlyInstancesOf(Stage::class, $response->items());
    }

    /**
     * @test
     */
    public function it_get_stages_as_collection(): void
    {
        Notification::fake();
        $board = Board::factory()->create();
        Stage::factory()->state(['board_id' => $board->id])->count(2)->create();

        $response = GetAllStages::make()->handle(
            GetAllStagesServiceDto::from([
                'board_id' => $board->id,
                'paginated' => false,
            ])
        );

        $this->assertCount(2, $response);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(Stage::class, $response);
    }

    /**
     * @test
     */
    public function it_get_stages_with_relations_loaded(): void
    {
        Notification::fake();
        $board = Board::factory()->create();
        Stage::factory()->state(['board_id' => $board->id])->count(2)->create();

        $response = GetAllStages::make()->handle(
            GetAllStagesServiceDto::from([
                'board_id' => $board->id,
                'relations' => ['author', 'tasks'],
                'paginated' => false,
            ])
        );

        $this->assertTrue($response->offsetGet(0)->relationLoaded('author'));
        $this->assertTrue($response->offsetGet(0)->relationLoaded('tasks'));
    }
}
