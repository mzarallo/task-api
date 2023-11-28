<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Lorisleiva\Actions\Concerns\AsAction;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshToken
{
    use AsAction;

    /**
     * Obtiene un token nuevo y pasa a lista negra el actual (blacklisted).
     */
    public function handle(): object
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }

    private function respondWithToken(mixed $token): object
    {
        return (object) [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }
}
