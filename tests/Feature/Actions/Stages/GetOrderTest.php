<?php

declare(strict_types=1);

use App\Actions\Stages\GetOrder;
use App\Data\Services\Stages\GetOrderServiceDto;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('get correct order when there are no stages', function () {
    $board = Board::factory()->create();

    $response = GetOrder::make()->handle(
        GetOrderServiceDto::from([
            'board_id' => $board->id,
            'order' => 1,
        ])
    );

    expect($response)->toBeInt()
        ->and($response)->toEqual(1);
});

test('it get correct order when there are stages', function () {
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

    expect($response)->toBeInt()
        ->and($response)->toEqual(2);
});
