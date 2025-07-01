<?php

declare(strict_types=1);

use App\Actions\Stages\CreateStage;
use App\Data\Services\Stages\CreateStageServiceDto;
use App\Models\Board;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('create a stage', function () {
    $board = Board::factory()->create();

    $response = CreateStage::make()->handle(
        CreateStageServiceDto::from([
            'name' => $this->faker->sentence,
            'hex_color' => $this->faker->hexColor,
            'board_id' => $board->id,
            'order' => 1,
            'author_id' => User::factory()->create()->id,
            'is_final_stage' => false,
        ])
    );

    expect($response)->toBeInstanceOf(Stage::class);
});
