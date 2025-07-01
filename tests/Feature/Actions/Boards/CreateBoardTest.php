<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards;

use App\Actions\Boards\CreateBoard;
use App\Data\Services\Boards\CreateBoardServiceDto;
use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateBoardTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_create_a_board(): void
    {
        $response = CreateBoard::make()->handle(
            CreateBoardServiceDto::from([
                'name' => $this->faker->sentence,
                'hex_color' => $this->faker->hexColor,
                'author_id' => User::factory()->create()->id,
            ])
        );

        $this->assertInstanceOf(Board::class, $response);
    }
}
