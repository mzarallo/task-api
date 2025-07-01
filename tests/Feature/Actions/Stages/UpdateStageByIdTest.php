<?php

declare(strict_types=1);

use App\Actions\Stages\UpdateStageById;
use App\Data\Services\Stages\UpdateStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseHas;

uses(WithFaker::class);

it('update stage by id', function () {
    $stage = Stage::factory()->create();
    $params = [
        'name' => $this->faker->name,
        'hex_color' => $this->faker->hexColor,
    ];

    $response = UpdateStageById::make()->handle(
        $stage->id,
        UpdateStageByIdServiceDto::from($params)
    );

    expect($response)->toBeInstanceOf(Stage::class);
    assertDatabaseHas('stages', $params);
});
