<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\CreateStage;
use App\Data\Services\Stages\CreateStageServiceDto;
use App\Models\Board;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateStageTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_create_a_stage(): void
    {
        $board = Board::factory()->create();

        $response = CreateStage::make()->handle(
            CreateStageServiceDto::from([
                'name' => $this->faker->sentence,
                'hex_color' => $this->faker->hexColor,
                'board_id' => $board->id,
                'order' => 1,
                'author_id' => User::factory()->create()->id,
                'is_final_stage' => false,
            ])
        );

        $this->assertInstanceOf(Stage::class, $response);
    }
}
