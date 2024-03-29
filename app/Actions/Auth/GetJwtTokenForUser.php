<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Auth\AuthenticationException;
use Lorisleiva\Actions\Concerns\AsAction;

class GetJwtTokenForUser
{
    use AsAction;

    /**
     * @throws AuthenticationException
     */
    public function handle(string $email, string $password): object
    {
        $token = $this->attemptLogin(['email' => $email, 'password' => $password]);
        if (! $token) {
            return throw new AuthenticationException('No autorizado');
        }

        return (object) $this->successLoginResponse($token);
    }

    private function attemptLogin(array $credentials)
    {
        return auth()->attempt($credentials);
    }

    private function successLoginResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }
}
