<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stages;

use App\Actions\Stages\OrderStages;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderStagesTest extends TestCase
{
    #[Test]
    public function it_order_stages(): void
    {
        $board = Board::factory()->has(
            Stage::factory()->state(new Sequence(
                ['order' => 10],
                ['order' => 8],
                ['order' => 11],
            ))->count(3)
        )->create();

        $response = OrderStages::make()->handle(
            $board->id
        );

        $this->assertCount(3, $response);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(Stage::class, $response);
        $this->assertEquals(1, $response->offsetGet(0)->order);
        $this->assertEquals(2, $response->offsetGet(1)->order);
        $this->assertEquals(3, $response->offsetGet(2)->order);
    }
}
