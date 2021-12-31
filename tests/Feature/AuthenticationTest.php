<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_user_can_obtain_jwt_token_with_correct_credentials(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', route('api.authentication.login'), [
            'email' => User::all()->random()->email,
            'password' => 'password',
        ]);

        $response->assertJson(fn (AssertableJson $json) => $json
                ->hasAll('token_type', 'access_token', 'expires_in')
                ->where('token_type', 'bearer')
                ->whereAllType([
                    'token_type' => 'string',
                    'expires_in' => 'integer',
                    'access_token' => 'string',
                ])
                ->etc()
        )->assertStatus(200);
    }

    public function test_user_cannot_get_jwt_token_with_incorrect_credentials(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', route('api.authentication.login'), [
            'email' => 'fakemail@email.com',
            'password' => 'password',
        ]);

        $response
            ->assertJson(['message' => 'Las credenciales no coinciden nuestros registros'])
            ->assertStatus(401);
    }
}
