<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\RefreshToken;
use Tests\TestCase;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenTest extends TestCase
{
    /**
     * @test
     */
    public function it_return_a_fresh_token(): void
    {
        JWTAuth::partialMock()->shouldReceive('getToken')->andReturn('old_token');
        JWTAuth::partialMock()->shouldReceive('refresh')->andReturn('refreshed_token');

        $response = RefreshToken::make()->handle();

        $this->assertIsObject($response);
        $this->assertObjectHasProperty('access_token', $response);
        $this->assertIsString($response->access_token);
        $this->assertEquals('refreshed_token', $response->access_token);
        $this->assertObjectHasProperty('token_type', $response);
        $this->assertEquals('bearer', $response->token_type);
        $this->assertObjectHasProperty('expires_in', $response);
        $this->assertIsInt($response->expires_in);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_token_doesnt_exist(): void
    {
        $this->expectException(JWTException::class);

        RefreshToken::make()->handle();
    }
}
