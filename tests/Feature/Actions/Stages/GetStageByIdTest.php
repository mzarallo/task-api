<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\GetStageById;
use App\Data\Services\Stages\GetStageByIdServiceDto;
use App\Models\Stage;
use Tests\TestCase;

class GetStageByIdTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_stage_by_id(): void
    {
        $stage = Stage::factory()->create();

        $response = GetStageById::make()->handle(
            GetStageByIdServiceDto::from([
                'stage_id' => $stage->id,
            ])
        );

        $this->assertInstanceOf(Stage::class, $response);
        $this->assertEquals($stage->id, $response->id);
    }

    /**
     * @test
     */
    public function it_get_stage_by_id_with_relations_loaded(): void
    {
        $stage = Stage::factory()->create();

        $response = GetStageById::make()->handle(
            GetStageByIdServiceDto::from([
                'stage_id' => $stage->id,
                'relations' => ['author', 'tasks'],
            ])
        );

        $this->assertTrue($response->relationLoaded('author'));
        $this->assertTrue($response->relationLoaded('tasks'));
    }
}
