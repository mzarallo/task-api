<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards;

use App\Actions\Boards\DeleteBoardById;
use App\Data\Services\Boards\DeleteBoardByIdServiceDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteBoardByIdTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_delete_a_board(): void
    {
        $board = Board::factory()->create();

        $response = DeleteBoardById::make()->handle(
            DeleteBoardByIdServiceDto::from([
                'board_id' => $board->id,
            ])
        );

        $this->assertTrue($response);
        $this->assertDatabaseEmpty('boards');
    }

    #[Test]
    public function it_throws_an_exception_for_board_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        DeleteBoardById::make()->handle(
            DeleteBoardByIdServiceDto::from([
                'board_id' => $this->faker->randomNumber(),
            ])
        );
    }
}
