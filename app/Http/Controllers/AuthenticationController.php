<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\GetJwtTokenForUserService;
use App\Http\Requests\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request, GetJwtTokenForUserService $getJwtTokenForUserService): JsonResponse
    {
        try {
            return response()->json($getJwtTokenForUserService->run($request->get('email'), $request->get('password')));
        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => 'Las credenciales no coinciden nuestros registros',
            ], 401);
        }
    }
}
