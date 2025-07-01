<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\postJson;

uses(DatabaseMigrations::class);
uses(WithFaker::class);

test('user can obtain jwt token with correct credentials', function () {
    $user = User::factory()->create();

    postJson(route('api.authentication.login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll('token_type', 'access_token', 'expires_in')
                ->where('token_type', 'bearer')
                ->whereAllType([
                    'token_type' => 'string',
                    'expires_in' => 'integer',
                    'access_token' => 'string',
                ])
                ->etc()
        );
});

test('user cannot get jwt token with incorrect credentials', function () {
    postJson(route('api.authentication.login'), [
        'email' => $this->faker->email,
        'password' => 'password',
    ])->assertUnauthorized()
        ->assertJson(['message' => 'Las credenciales no coinciden nuestros registros']);
});
