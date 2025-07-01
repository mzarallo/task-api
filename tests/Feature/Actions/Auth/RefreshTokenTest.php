<?php

declare(strict_types=1);

use App\Actions\Auth\RefreshToken;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

it('return a fresh token', function () {
    JWTAuth::partialMock()->shouldReceive('getToken')->andReturn('old_token');
    JWTAuth::partialMock()->shouldReceive('refresh')->andReturn('refreshed_token');

    $response = RefreshToken::make()->handle();

    expect($response)->toBeObject()
        ->and($response)->toHaveProperties([
            'access_token',
            'token_type',
            'expires_in',
        ])->and($response->access_token)->toBeString()
        ->and($response->access_token)->toEqual('refreshed_token')
        ->and($response->token_type)->toEqual('bearer')
        ->and($response->expires_in)->toBeInt();
});

it('throws exception if token doesnt exist', function () {
    RefreshToken::make()->handle();
})->throws(JWTException::class);
