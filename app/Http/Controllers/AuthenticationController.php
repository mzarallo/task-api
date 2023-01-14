<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\GetJwtTokenForUser;
use App\Actions\Auth\RefreshToken;
use App\Http\Requests\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request, GetJwtTokenForUser $getJwtTokenForUser): JsonResponse
    {
        try {
            return response()->json($getJwtTokenForUser->handle($request->get('email'), $request->get('password')));
        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => 'Las credenciales no coinciden nuestros registros',
            ], 401);
        }
    }

    public function refresh(RefreshToken $refreshTokenService): JsonResponse
    {
        try {
            $token = $refreshTokenService->handle();

            return response()->json(['token' => $token]);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['message' => 'Token blacklisted'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token cannot be refreshed'], 401);
        }
    }
}
