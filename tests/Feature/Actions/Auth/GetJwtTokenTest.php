<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\GetJwtTokenForUser;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetJwtTokenTest extends TestCase
{
    #[Test]
    public function it_returns_a_jwt_token_for_a_valid_login()
    {
        $user = User::factory()->create();

        $response = GetJwtTokenForUser::make()->handle($user->email, 'password');

        $this->assertIsObject($response);
        $this->assertObjectHasProperty('access_token', $response);
        $this->assertIsString($response->access_token);
        $this->assertObjectHasProperty('token_type', $response);
        $this->assertEquals('bearer', $response->token_type);
        $this->assertObjectHasProperty('expires_in', $response);
        $this->assertIsInt($response->expires_in);
    }

    #[Test]
    public function it_throws_an_exception_for_an_invalid_login()
    {
        $user = User::factory()->create();

        $this->expectException(AuthenticationException::class);
        $response = GetJwtTokenForUser::make()->handle($user->email, 'incorrect');
    }
}
