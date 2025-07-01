<?php

declare(strict_types=1);

use App\Actions\Users\GetUserById;
use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Models\User;

it('get user by id', function () {
    $user = User::factory()->create();

    $response = GetUserById::make()->handle(
        GetUserByIdServiceDto::from([
            'user_id' => $user->id,
        ])
    );

    expect($response)->toBeInstanceOf(User::class)
        ->and($response->id)->toEqual($user->id);
});

it('get user by id with relations loaded', function () {
    $user = User::factory()->create();

    $response = GetUserById::make()->handle(
        GetUserByIdServiceDto::from([
            'user_id' => $user->id,
            'relations' => ['boards'],
        ])
    );

    expect($response->relationLoaded('boards'))->toBeTrue();
});
