<?php

declare(strict_types=1);

use App\Actions\Stages\OrderStages;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection;

it('order stages', function () {
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

    expect($response)->toHaveCount(3)
        ->and($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(Stage::class)
        ->and($response->offsetGet(0)->order)->toEqual(1)
        ->and($response->offsetGet(1)->order)->toEqual(2)
        ->and($response->offsetGet(2)->order)->toEqual(3);
});
