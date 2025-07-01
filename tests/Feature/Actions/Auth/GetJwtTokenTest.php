<?php

declare(strict_types=1);

use App\Actions\Auth\GetJwtTokenForUser;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

it('returns a jwt token for a valid login', function () {
    $user = User::factory()->create();

    $response = GetJwtTokenForUser::make()->handle($user->email, 'password');

    expect($response)->toBeObject()
        ->and($response)->toHaveProperties([
            'access_token',
            'token_type',
            'expires_in',
        ])
        ->and($response->access_token)->toBeString()
        ->and($response->token_type)->toEqual('bearer')
        ->and($response->expires_in)->toBeInt();
});

it('throws an exception for an invalid login', function () {
    $user = User::factory()->create();

    GetJwtTokenForUser::make()->handle($user->email, 'incorrect');
})->throws(AuthenticationException::class);
