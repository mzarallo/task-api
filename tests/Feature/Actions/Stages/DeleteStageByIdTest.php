<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\DeleteStageById;
use App\Data\Services\Stages\DeleteStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteStageByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_delete_a_stage(): void
    {
        $stage = Stage::factory()->create();

        $response = DeleteStageById::make()->handle(
            DeleteStageByIdServiceDto::from([
                'stage_id' => $stage->id,
            ])
        );

        $this->assertTrue($response);
        $this->assertDatabaseEmpty('stages');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_stage_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        DeleteStageById::make()->handle(
            DeleteStageByIdServiceDto::from([
                'stage_id' => $this->faker->randomNumber(),
            ])
        );
    }
}
