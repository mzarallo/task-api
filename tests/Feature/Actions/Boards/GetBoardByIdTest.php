<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards;

use App\Actions\Boards\GetBoardById;
use App\Data\Services\Boards\GetBoardByIdServiceDto;
use App\Models\Board;
use Tests\TestCase;

class GetBoardByIdTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_board_by_id(): void
    {
        $board = Board::factory()->create();

        $response = GetBoardById::make()->handle(
            GetBoardByIdServiceDto::from([
                'board_id' => $board->id,
            ])
        );

        $this->assertInstanceOf(Board::class, $response);
        $this->assertEquals($board->id, $response->id);
    }

    /**
     * @test
     */
    public function it_get_board_by_id_with_relations_loaded(): void
    {
        $board = Board::factory()->create();

        $response = GetBoardById::make()->handle(
            GetBoardByIdServiceDto::from([
                'board_id' => $board->id,
                'relations' => ['author', 'stages'],
            ])
        );

        $this->assertTrue($response->relationLoaded('author'));
        $this->assertTrue($response->relationLoaded('stages'));
    }
}
