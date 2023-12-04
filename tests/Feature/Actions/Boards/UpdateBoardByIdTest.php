<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards;

use App\Actions\Boards\UpdateBoardById;
use App\Data\Services\Boards\UpdateBoardServiceDto;
use App\Models\Board;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateBoardByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_update_board_by_id(): void
    {
        $board = Board::factory()->create();
        $params = [
            'name' => $this->faker->name,
            'hex_color' => $this->faker->hexColor,
        ];

        $response = UpdateBoardById::make()->handle(
            $board->id,
            UpdateBoardServiceDto::from($params)
        );

        $this->assertInstanceOf(Board::class, $response);
        $this->assertDatabaseHas('boards', $params);
    }
}
