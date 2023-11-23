<?php

declare(strict_types=1);

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /**
     * @test
     */
    public function user_can_obtain_jwt_token_with_correct_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.authentication.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertJson(
            fn (AssertableJson $json) => $json
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

    /**
     * @test
     */
    public function user_cannot_get_jwt_token_with_incorrect_credentials(): void
    {
        $response = $this->postJson(route('api.authentication.login'), [
            'email' => $this->faker->email,
            'password' => 'password',
        ]);

        $response
            ->assertJson(['message' => 'Las credenciales no coinciden nuestros registros'])
            ->assertStatus(401);
    }
}
