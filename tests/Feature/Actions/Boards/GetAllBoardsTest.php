<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards;

use App\Actions\Boards\GetAllBoards;
use App\Data\Services\Boards\GetAllBoardsServiceDto;
use App\Models\Board;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetAllBoardsTest extends TestCase
{
    #[Test]
    public function it_get_boards_paginated(): void
    {
        Board::factory()->count(2)->create();
        Notification::fake();

        $response = GetAllBoards::make()->handle(
            GetAllBoardsServiceDto::from([
                'paginated' => true,
            ])
        );

        $this->assertCount(2, $response->items());
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertContainsOnlyInstancesOf(Board::class, $response->items());
    }

    #[Test]
    public function it_get_boards_as_collection(): void
    {
        Board::factory()->count(2)->create();
        Notification::fake();

        $response = GetAllBoards::make()->handle(
            GetAllBoardsServiceDto::from([
                'paginated' => false,
            ])
        );

        $this->assertCount(2, $response);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(Board::class, $response);
    }

    #[Test]
    public function it_get_boards_with_relations_loaded(): void
    {
        Board::factory()->create();
        Notification::fake();

        $response = GetAllBoards::make()->handle(
            GetAllBoardsServiceDto::from([
                'relations' => ['stages', 'author'],
                'paginated' => false,
            ])
        );

        $this->assertTrue($response->offsetGet(0)->relationLoaded('author'));
        $this->assertTrue($response->offsetGet(0)->relationLoaded('stages'));
    }
}
