<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\UpdateStageById;
use App\Data\Services\Stages\UpdateStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateStageByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_update_stage_by_id(): void
    {
        $stage = Stage::factory()->create();
        $params = [
            'name' => $this->faker->name,
            'hex_color' => $this->faker->hexColor,
        ];

        $response = UpdateStageById::make()->handle(
            $stage->id,
            UpdateStageByIdServiceDto::from($params)
        );

        $this->assertInstanceOf(Stage::class, $response);
        $this->assertDatabaseHas('stages', $params);
    }
}
