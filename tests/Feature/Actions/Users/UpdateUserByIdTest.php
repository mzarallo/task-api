<?php

declare(strict_types=1);

use App\Actions\Users\UpdateUserById;
use App\Data\Services\Users\UpdateUserByIdServiceDto;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseHas;

uses(WithFaker::class);

it('update user by id', function () {
    $user = User::factory()->create();
    $params = [
        'name' => $this->faker->name,
        'last_name' => $this->faker->lastName,
    ];

    $response = UpdateUserById::make()->handle(
        $user->id,
        UpdateUserByIdServiceDto::from($params)
    );

    expect($response)->toBeInstanceOf(User::class);
    assertDatabaseHas('users', $params);
});
