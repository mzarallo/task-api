<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\GetOrder;
use App\Data\Services\Stages\GetOrderServiceDto;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class GetOrderTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_correct_order_when_there_are_no_stages(): void
    {
        $board = Board::factory()->create();

        $response = GetOrder::make()->handle(
            GetOrderServiceDto::from([
                'board_id' => $board->id,
                'order' => 1,
            ])
        );

        $this->assertIsInt($response);
        $this->assertEquals(1, $response);
    }

    public function test_it_get_correct_order_when_there_are_stages(): void
    {
        $board = Board::factory()->has(
            Stage::factory()->state(new Sequence(
                ['order' => 1, 'is_final_stage' => false],
                ['order' => 2, 'is_final_stage' => true],
            ))->count(2)
        )->create();

        $response = GetOrder::make()->handle(
            GetOrderServiceDto::from([
                'board_id' => $board->id,
                'order' => 3,
            ])
        );

        $this->assertIsInt($response);
        $this->assertEquals(2, $response);
    }
}
