<?php

declare(strict_types=1);

use App\Actions\Stages\GetStageById;
use App\Data\Services\Stages\GetStageByIdServiceDto;
use App\Models\Stage;

it('get stage by id', function () {
    $stage = Stage::factory()->create();

    $response = GetStageById::make()->handle(
        GetStageByIdServiceDto::from([
            'stage_id' => $stage->id,
        ])
    );

    expect($response)->toBeInstanceOf(Stage::class)
        ->and($response->id)->toEqual($stage->id);
});

it('get stage by id with relations loaded', function () {
    $stage = Stage::factory()->create();

    $response = GetStageById::make()->handle(
        GetStageByIdServiceDto::from([
            'stage_id' => $stage->id,
            'relations' => ['author', 'tasks'],
        ])
    );

    expect($response->relationLoaded('author'))->toBeTrue()
        ->and($response->relationLoaded('tasks'))->toBeTrue();
});
