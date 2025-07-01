<?php

declare(strict_types=1);

use App\Actions\Users\DeleteUserById;
use App\Data\Services\Users\DeleteUserByIdServiceDto;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseEmpty;

uses(WithFaker::class);

it('delete user by id', function () {
    $user = User::factory()->create();

    $response = DeleteUserById::make()->handle(
        DeleteUserByIdServiceDto::from([
            'user_id' => $user->id,
        ])
    );

    expect($response)->toBeTrue();
    assertDatabaseEmpty('users');
});

it('throws an exception for user not found', function () {
    DeleteUserById::make()->handle(
        DeleteUserByIdServiceDto::from([
            'user_id' => $this->faker->randomNumber(),
        ])
    );
})->throws(ModelNotFoundException::class);
