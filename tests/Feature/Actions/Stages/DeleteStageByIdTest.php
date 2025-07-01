<?php

declare(strict_types=1);

use App\Actions\Stages\DeleteStageById;
use App\Data\Services\Stages\DeleteStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseEmpty;

uses(WithFaker::class);

it('delete a stage', function () {
    $stage = Stage::factory()->create();

    $response = DeleteStageById::make()->handle(
        DeleteStageByIdServiceDto::from([
            'stage_id' => $stage->id,
        ])
    );

    expect($response)->toBeTrue();
    assertDatabaseEmpty('stages');
});

it('throws an exception for stage not found', function () {
    DeleteStageById::make()->handle(
        DeleteStageByIdServiceDto::from([
            'stage_id' => $this->faker->randomNumber(),
        ])
    );
})->throws(ModelNotFoundException::class);
